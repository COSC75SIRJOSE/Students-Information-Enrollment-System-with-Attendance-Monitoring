@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')

@php
    $selectedStudent = $studentId
        ? $students->firstWhere('id', $studentId)
        : null;
@endphp

<div class="page-heading">
    <div>
        <h1>Attendance Reports</h1>
        <p>Review and filter student attendance records.</p>
    </div>

    <a href="{{ route('attendance.index') }}"
       class="button secondary">
        Back to Attendance
    </a>
</div>

{{-- Report Filters --}}
<div class="card report-filters" style="margin-bottom: 20px;">
    <form action="{{ route('attendance.reports') }}"
          method="GET"
          style="display: flex; flex-wrap: wrap; gap: 15px; align-items: end;">

        <div class="field">
            <label for="from">From Date</label>
            <input type="date"
                   id="from"
                   name="from"
                   value="{{ $from }}">
        </div>

        <div class="field">
            <label for="to">To Date</label>
            <input type="date"
                   id="to"
                   name="to"
                   value="{{ $to }}">
        </div>

        <div class="field">
            <label for="student_id">Student</label>
            <select id="student_id" name="student_id">
                <option value="">All Students</option>

                @foreach ($students as $student)
                    <option value="{{ $student->id }}"
                        @selected((string) $studentId === (string) $student->id)>
                        {{ $student->student_number }} -
                        {{ $student->last_name }},
                        {{ $student->first_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="button primary">
            Generate Report
        </button>

        <a href="{{ route('attendance.reports') }}"
           class="button secondary">
            Clear Filters
        </a>
    </form>
</div>

{{-- Information shown in the printed report --}}
<div class="report-print-meta">
    <p>
        <strong>Report Period:</strong>
        {{ $from ? \Illuminate\Support\Carbon::parse($from)->format('M d, Y') : 'All dates' }}
        to
        {{ $to ? \Illuminate\Support\Carbon::parse($to)->format('M d, Y') : 'All dates' }}
    </p>

    <p>
        <strong>Student:</strong>
        @if ($selectedStudent)
            {{ $selectedStudent->student_number }} -
            {{ $selectedStudent->last_name }},
            {{ $selectedStudent->first_name }}
        @else
            All Students
        @endif
    </p>
</div>

{{-- Attendance Summary --}}
<div class="report-summary"
     style="display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 25px;">

    <div class="card">
        <p style="color: #64748b; margin: 0;">Total Records</p>
        <h2>{{ $reportCounts['total'] }}</h2>
    </div>

    <div class="card">
        <p style="color: #15803d; margin: 0;">Present</p>
        <h2 style="color: #15803d;">
            {{ $reportCounts['present'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #b91c1c; margin: 0;">Absent</p>
        <h2 style="color: #b91c1c;">
            {{ $reportCounts['absent'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #c2410c; margin: 0;">Late</p>
        <h2 style="color: #c2410c;">
            {{ $reportCounts['late'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #6d28d9; margin: 0;">Excused</p>
        <h2 style="color: #6d28d9;">
            {{ $reportCounts['excused'] }}
        </h2>
    </div>

</div>

{{-- Attendance Records --}}
<div class="card report-records">

    <div class="report-records-heading"
         style="display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 12px;
                margin-bottom: 20px;">

        <div>
            <h2 style="margin: 0;">Attendance Records</h2>
            <p style="color: #64748b; margin-bottom: 0;">
                {{ $records->count() }} record(s) found.
            </p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">

    <a href="{{ route('attendance.reports.export', request()->only('from', 'to', 'student_id')) }}"
       class="button secondary">
        Export CSV
    </a>

    <button type="button"
            class="button primary"
            onclick="window.print()">
        Print Report
    </button>

</div>

    <div class="report-table-wrap" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Student Number</th>
                    <th>Student Name</th>
                    <th>Status</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Remarks</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($records as $record)
                    <tr>
                        <td>
                            {{ \Illuminate\Support\Carbon::parse($record->attendance_date)->format('M d, Y') }}
                        </td>

                        <td>
                            {{ $record->student?->student_number ?? '—' }}
                        </td>

                        <td>
                            @if ($record->student)
                                {{ $record->student->last_name }},
                                {{ $record->student->first_name }}
                                {{ $record->student->middle_name }}
                                {{ $record->student->suffix }}
                            @else
                                —
                            @endif
                        </td>

                        <td>{{ ucfirst($record->status) }}</td>

                        <td>
                            {{ $record->time_in
                                ? \Illuminate\Support\Carbon::parse($record->time_in)->format('g:i A')
                                : '—' }}
                        </td>

                        <td>
                            {{ $record->time_out
                                ? \Illuminate\Support\Carbon::parse($record->time_out)->format('g:i A')
                                : '—' }}
                        </td>

                        <td>{{ $record->remarks ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7"
                            style="text-align: center; padding: 25px;">
                            No attendance records found for the selected filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<style>
    .report-print-meta {
        display: none;
    }

    @page {
        size: landscape;
        margin: 12mm;
    }

    @media print {
        html,
        body {
            background: #ffffff !important;
            color: #111827 !important;
            font-size: 10pt !important;
        }

        nav,
        header,
        footer,
        .button,
        .report-filters {
            display: none !important;
        }

        .page-heading {
            margin: 0 0 12px !important;
        }

        .page-heading h1 {
            font-size: 21pt !important;
            margin-bottom: 5px !important;
        }

        .page-heading p {
            font-size: 10pt !important;
            margin: 0 !important;
        }

        .report-print-meta {
            display: block !important;
            margin: 10px 0 14px !important;
            font-size: 9pt !important;
        }

        .report-print-meta p {
            margin: 3px 0 !important;
        }

        .report-summary {
            display: grid !important;
            grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
            gap: 8px !important;
            margin: 12px 0 16px !important;
            break-inside: avoid;
        }

        .report-summary .card {
            min-width: 0 !important;
            padding: 10px !important;
            margin: 0 !important;
        }

        .report-summary p {
            font-size: 9pt !important;
        }

        .report-summary h2 {
            font-size: 16pt !important;
            margin: 6px 0 0 !important;
        }

        .card,
        .report-records {
            box-shadow: none !important;
            border: 1px solid #d1d5db !important;
        }

        .report-records {
            width: 100% !important;
            padding: 12px !important;
            margin: 0 !important;
            break-inside: auto !important;
        }

        .report-records-heading {
            margin-bottom: 10px !important;
        }

        .report-records-heading h2 {
            font-size: 15pt !important;
        }

        .report-table-wrap {
            width: 100% !important;
            overflow: visible !important;
        }

        .report-records table {
            width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
            font-size: 8.5pt !important;
        }

        .report-records th,
        .report-records td {
            padding: 6px 5px !important;
            border-bottom: 1px solid #d1d5db !important;
            text-align: left !important;
            white-space: normal !important;
            overflow-wrap: anywhere !important;
        }

        .report-records th:nth-child(1),
        .report-records td:nth-child(1) {
            width: 12%;
        }

        .report-records th:nth-child(2),
        .report-records td:nth-child(2) {
            width: 13%;
        }

        .report-records th:nth-child(3),
        .report-records td:nth-child(3) {
            width: 23%;
        }

        .report-records th:nth-child(4),
        .report-records td:nth-child(4) {
            width: 10%;
        }

        .report-records th:nth-child(5),
        .report-records td:nth-child(5) {
            width: 12%;
        }

        .report-records th:nth-child(6),
        .report-records td:nth-child(6) {
            width: 12%;
        }

        .report-records th:nth-child(7),
        .report-records td:nth-child(7) {
            width: 18%;
        }

        .report-records tr {
            break-inside: avoid;
        }

        .report-records thead {
            display: table-header-group;
        }
    }
</style>

@endsection
