@extends('layouts.app')

@section('title', 'Edit Academic Year')

@section('content')

<div class="page-heading">
    <div>
        <h1>Edit Academic Year</h1>
        <p>Update the school year information.</p>
    </div>

    <a href="{{ route('academic-years.index') }}"
       class="button secondary">
        Back to Academic Years
    </a>
</div>

<div class="card">
    @if ($errors->any())
        <div style="color: #b91c1c; margin-bottom: 20px;">
            <strong>Please correct these errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('academic-years.update', $academicYear) }}"
          method="POST">
        @csrf
        @method('PUT')

        <div class="field">
            <label for="name">Academic Year</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $academicYear->name) }}"
                   maxlength="20"
                   required>
        </div>

        <div class="field">
            <label for="start_date">Start Date</label>
            <input type="date"
                   id="start_date"
                   name="start_date"
                   value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}"
                   required>
        </div>

        <div class="field">
            <label for="end_date">End Date</label>
            <input type="date"
                   id="end_date"
                   name="end_date"
                   value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}"
                   required>
        </div>

        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="planned"
                    @selected(old('status', $academicYear->status) === 'planned')>
                    Planned
                </option>

                <option value="active"
                    @selected(old('status', $academicYear->status) === 'active')>
                    Active
                </option>

                <option value="completed"
                    @selected(old('status', $academicYear->status) === 'completed')>
                    Completed
                </option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="button primary">
                Update Academic Year
            </button>

            <a href="{{ route('academic-years.index') }}"
               class="button secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection