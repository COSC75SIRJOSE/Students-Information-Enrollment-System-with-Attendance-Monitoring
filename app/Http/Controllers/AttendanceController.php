<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'date' => ['nullable', 'date'],
        ]);

        $date = $request->query('date') ?: now()->toDateString();

        $students = Student::with([
            'attendances' => function ($query) use ($date) {
                $query->whereDate('attendance_date', $date);
            }
        ])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $attendanceRecords = collect();

        foreach ($students as $student) {
            $record = $student->attendances->first();

            if ($record) {
                $attendanceRecords->push($record);
            }
        }

        $attendanceCounts = [
            'total' => $students->count(),
            'present' => $attendanceRecords->where('status', 'present')->count(),
            'absent' => $attendanceRecords->where('status', 'absent')->count(),
            'late' => $attendanceRecords->where('status', 'late')->count(),
            'excused' => $attendanceRecords->where('status', 'excused')->count(),
            'unmarked' => $students->count() - $attendanceRecords->count(),
        ];

        return view('attendance.index', compact(
            'students',
            'date',
            'attendanceCounts'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attendance_date' => ['required', 'date'],
            'attendance' => ['required', 'array', 'min:1'],

            'attendance.*.student_id' => [
                'required',
                'integer',
                'distinct',
                'exists:students,id',
            ],

            'attendance.*.status' => [
                'required',
                Rule::in(['present', 'absent', 'late', 'excused']),
            ],

            'attendance.*.time_in' => [
                'nullable',
                'date_format:H:i',
            ],

            'attendance.*.time_out' => [
                'nullable',
                'date_format:H:i',
            ],

            'attendance.*.remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        foreach ($validated['attendance'] as $row) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $row['student_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'status' => $row['status'],
                    'time_in' => $row['time_in'] ?? null,
                    'time_out' => $row['time_out'] ?? null,
                    'remarks' => $row['remarks'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('attendance.index', [
                'date' => $validated['attendance_date'],
            ])
            ->with('success', 'Attendance saved successfully.');
    }

    public function reports(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'student_id' => [
                'nullable',
                'integer',
                'exists:students,id',
            ],
        ]);

        $from = $validated['from'] ?? null;
        $to = $validated['to'] ?? null;
        $studentId = $validated['student_id'] ?? null;

        $students = Student::orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $query = Attendance::with('student');

        if ($from) {
            $query->whereDate('attendance_date', '>=', $from);
        }

        if ($to) {
            $query->whereDate('attendance_date', '<=', $to);
        }

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $records = $query
            ->orderByDesc('attendance_date')
            ->orderBy('student_id')
            ->get();

        $reportCounts = [
            'total' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'excused' => $records->where('status', 'excused')->count(),
        ];

        return view('attendance.reports', compact(
            'records',
            'students',
            'from',
            'to',
            'studentId',
            'reportCounts'
        ));
    }

    public function exportCsv(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'student_id' => [
                'nullable',
                'integer',
                'exists:students,id',
            ],
        ]);

        $query = Attendance::with('student');

        if (!empty($validated['from'])) {
            $query->whereDate(
                'attendance_date',
                '>=',
                $validated['from']
            );
        }

        if (!empty($validated['to'])) {
            $query->whereDate(
                'attendance_date',
                '<=',
                $validated['to']
            );
        }

        if (!empty($validated['student_id'])) {
            $query->where(
                'student_id',
                $validated['student_id']
            );
        }

        $records = $query
            ->orderByDesc('attendance_date')
            ->orderBy('student_id')
            ->get();

        $filename = 'attendance-report-' .
            now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(
            function () use ($records) {
                $handle = fopen('php://output', 'w');

                fwrite($handle, "\xEF\xBB\xBF");

                fputcsv($handle, [
                    'Date',
                    'Student Number',
                    'Student Name',
                    'Status',
                    'Time In',
                    'Time Out',
                    'Remarks',
                ], ',', '"', '');

                foreach ($records as $record) {
                    $student = $record->student;

                    $studentName = $student
                        ? trim(
                            $student->last_name . ', ' .
                            $student->first_name . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            ($student->suffix ?? '')
                        )
                        : '';

                    fputcsv($handle, [
                        Carbon::parse($record->attendance_date)
                            ->format('Y-m-d'),
                        $student?->student_number ?? '',
                        $studentName,
                        ucfirst($record->status),
                        $record->time_in ?? '',
                        $record->time_out ?? '',
                        $record->remarks ?? '',
                    ], ',', '"', '');
                }

                fclose($handle);
            },
            $filename,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        );
    }
}
