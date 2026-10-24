@extends('layouts.app')

@section('title', 'Academic Terms')

@section('content')

<div class="page-heading">
    <div>
        <h1>Academic Terms</h1>
        <p>Manage semesters and enrollment periods.</p>
    </div>

    <a href="{{ route('academic-terms.create') }}"
       class="button primary">
        + Add Academic Term
    </a>
</div>

@if (session('success'))
    <div class="card" style="margin-bottom: 20px; color: #15803d;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="card" style="margin-bottom: 20px; color: #b91c1c;">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="card" style="margin-bottom: 20px; color: #b91c1c;">
        <strong>Please correct these errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card" style="margin-bottom: 20px;">
    <form action="{{ route('academic-terms.index') }}"
          method="GET"
          style="display: flex; gap: 12px; align-items: end; flex-wrap: wrap;">

        <div class="field">
            <label for="academic_year_id">Academic Year</label>

            <select id="academic_year_id" name="academic_year_id">
                <option value="">All Academic Years</option>

                @foreach ($academicYears as $year)
                    <option value="{{ $year->id }}"
                        @selected((string) $selectedAcademicYearId === (string) $year->id)>
                        {{ $year->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="button primary">
            Filter
        </button>

        <a href="{{ route('academic-terms.index') }}"
           class="button secondary">
            Clear Filter
        </a>
    </form>
</div>

@if ($academicYears->isEmpty())
    <div class="card">
        <h3>No Academic Years Found</h3>
        <p>Create an academic year before adding academic terms.</p>

        <a href="{{ route('academic-years.index') }}"
           class="button primary">
            Go to Academic Years
        </a>
    </div>
@else
    <div class="card">
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Term Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($terms as $term)
                        <tr>
                            <td>
                                {{ $term->academicYear?->name ?? '—' }}
                            </td>

                            <td>{{ $term->name }}</td>

                            <td>
                                {{ $term->start_date->format('M d, Y') }}
                            </td>

                            <td>
                                {{ $term->end_date->format('M d, Y') }}
                            </td>

                            <td>{{ ucfirst($term->status) }}</td>

                            <td>
                                <a href="{{ route('academic-terms.edit', $term) }}"
                                   class="button secondary">
                                    Edit
                                </a>

                                <form action="{{ route('academic-terms.destroy', $term) }}"
                                      method="POST"
                                      style="display: inline;"
                                      onsubmit="return confirm('Are you sure you want to delete this academic term?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="button secondary"
                                            style="color: #b91c1c;">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 25px;">
                                No academic terms registered yet.
                                Click "Add Academic Term" to begin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

@endsection