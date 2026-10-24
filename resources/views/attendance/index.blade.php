
@extends('layouts.app')

@section('title', 'Attendance Monitoring')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Attendance Monitoring</h1>
            <p>Record and manage daily student attendance.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(145px, 1fr)); gap: 15px; margin-bottom: 25px;">

    <div class="card">
        <p style="color: #64748b; margin: 0;">Total Students</p>
        <h2 style="margin: 10px 0 0; color: #1e3a8a;">
            {{ $attendanceCounts['total'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #15803d; margin: 0;">Present</p>
        <h2 style="margin: 10px 0 0; color: #15803d;">
            {{ $attendanceCounts['present'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #b91c1c; margin: 0;">Absent</p>
        <h2 style="margin: 10px 0 0; color: #b91c1c;">
            {{ $attendanceCounts['absent'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #c2410c; margin: 0;">Late</p>
        <h2 style="margin: 10px 0 0; color: #c2410c;">
            {{ $attendanceCounts['late'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #6d28d9; margin: 0;">Excused</p>
        <h2 style="margin: 10px 0 0; color: #6d28d9;">
            {{ $attendanceCounts['excused'] }}
        </h2>
    </div>

    <div class="card">
        <p style="color: #475569; margin: 0;">Unmarked</p>
        <h2 style="margin: 10px 0 0; color: #475569;">
            {{ $attendanceCounts['unmarked'] }}
        </h2>
    </div>

</div>

    @if ($errors->any())
        <div class="card" style="margin-bottom: 20px;">
            <strong>Please correct the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="margin-bottom: 20px;">
        <form action="{{ route('attendance.index') }}" method="GET"
              style="display: flex; align-items: end; gap: 12px; flex-wrap: wrap;">
            <div class="field">
                <label for="date">Attendance Date</label>
                <input type="date" id="date" name="date"
                       value="{{ $date }}" required>
            </div>

            <button type="submit" class="button primary">
                View Attendance
            </button>
        </form>
    </div>

    @if ($students->isEmpty())
        <div class="card">
            <h3>No Students Found</h3>
            <p>Register students before recording attendance.</p>
            <a href="{{ route('students.create') }}" class="button primary">
                Register Student
            </a>
        </div>
    @else
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf

            <input type="hidden" name="attendance_date" value="{{ $date }}">

            <div class="card">
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Student Number</th>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($students as $student)
                                @php
                                    $record = $student->attendances->first();
                                    $i = $loop->index;
                                @endphp

                                <tr>
                                    <td>{{ $student->student_number }}</td>

                                    <td>
                                        {{ $student->last_name }},
                                        {{ $student->first_name }}
                                        {{ $student->middle_name }}
                                        {{ $student->suffix }}
                                    </td>

                                    <td>
                                        <input type="hidden"
                                            name="attendance[{{ $i }}][student_id]"
                                            value="{{ $student->id }}">

                                        <select
                                            name="attendance[{{ $i }}][status]"
                                            required>
                                            @foreach ([
                                                'present' => 'Present',
                                                'absent' => 'Absent',
                                                'late' => 'Late',
                                                'excused' => 'Excused'
                                            ] as $value => $label)
                                                <option value="{{ $value }}"
                                                    @selected(old(
                                                        'attendance.' . $i . '.status',
                                                        $record?->status ?? 'present'
                                                    ) === $value)>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="time"
                                            name="attendance[{{ $i }}][time_in]"
                                            value="{{ old(
                                                'attendance.' . $i . '.time_in',
                                                substr((string) ($record?->time_in ?? ''), 0, 5)
                                            ) }}">
                                    </td>

                                    <td>
                                        <input type="time"
                                            name="attendance[{{ $i }}][time_out]"
                                            value="{{ old(
                                                'attendance.' . $i . '.time_out',
                                                substr((string) ($record?->time_out ?? ''), 0, 5)
                                            ) }}">
                                    </td>

                                    <td>
                                        <input type="text"
                                            name="attendance[{{ $i }}][remarks]"
                                            value="{{ old(
                                                'attendance.' . $i . '.remarks',
                                                $record?->remarks ?? ''
                                            ) }}"
                                            maxlength="500"
                                            placeholder="Optional">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="button primary">
                        Save Attendance
                    </button>
                </div>
            </div>
        </form>
    @endif
@endsection
