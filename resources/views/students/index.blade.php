@extends('layouts.app')

@section('title', 'Student Records')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Student Records</h1>
            <p>Manage student profiles and basic information.</p>
        </div>

        <a href="{{ route('students.create') }}" class="button primary">
            + Register Student
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student Number</th>
                        <th>Full Name</th>
                        <th>Date of Birth</th>
                        <th>Sex</th>
                        <th>Guardian</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>{{ $student->student_number }}</td>
                            <td>
                                {{ $student->last_name }},
                                {{ $student->first_name }}
                                {{ $student->middle_name }}
                                {{ $student->suffix }}
                            </td>
                            <td>
                                {{ $student->date_of_birth?->format('M d, Y') ?? '—' }}
                            </td>
                            <td>{{ $student->sex ?? '—' }}</td>
                            <td>{{ $student->guardian_name ?? '—' }}</td>
                            <td>
                                <span class="status status-{{ $student->status }}">
                                    {{ ucfirst($student->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a
                                        href="{{ route('students.edit', $student) }}"
                                        class="button secondary small-button">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('students.destroy', $student) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this student?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="button danger small-button">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                No student records yet. Select "Register Student" to add your first record.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $students->links() }}
        </div>
    </div>
@endsection