@extends('layouts.app')

@section('title', 'Academic Years')

@section('content')

<div class="page-heading">
    <div>
        <h1>Academic Years</h1>
        <p>Manage school years and their enrollment periods.</p>
    </div>

    <a href="{{ route('academic-years.create') }}"
       class="button primary">
        + Add Academic Year
    </a>
</div>

@if (session('success'))
    <div class="card"
         style="margin-bottom: 20px; background: #dcfce7; color: #166534;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="card"
         style="margin-bottom: 20px; color: #b91c1c;">
        <strong>Please correct the following errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Academic Year</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Terms</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($academicYears as $academicYear)
                    <tr>
                        <td>
                            <strong>{{ $academicYear->name }}</strong>
                        </td>

                        <td>
                            {{ $academicYear->start_date->format('M d, Y') }}
                        </td>

                        <td>
                            {{ $academicYear->end_date->format('M d, Y') }}
                        </td>

                        <td>{{ $academicYear->terms_count }}</td>

                        <td>
                            {{ ucfirst($academicYear->status) }}
                        </td>

                        <td>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a href="{{ route('academic-years.edit', $academicYear) }}"
                                   class="button secondary">
                                    Edit
                                </a>

                                <form action="{{ route('academic-years.destroy', $academicYear) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this academic year? Its associated terms may also be deleted.');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="button"
                                            style="background: #fee2e2; color: #b91c1c;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            style="text-align: center; padding: 25px;">
                            No academic years registered yet.
                            Click "Add Academic Year" to begin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
