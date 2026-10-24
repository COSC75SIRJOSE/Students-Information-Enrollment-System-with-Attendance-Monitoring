@extends('layouts.app')

@section('title', 'Add Academic Year')

@section('content')

<div class="page-heading">
    <div>
        <h1>Add Academic Year</h1>
        <p>Register a new school year.</p>
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

    <form action="{{ route('academic-years.store') }}"
          method="POST">
        @csrf

        <div class="field">
            <label for="name">Academic Year</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   placeholder="Example: 2026-2027"
                   maxlength="20"
                   required>
        </div>

        <div class="field">
            <label for="start_date">Start Date</label>
            <input type="date"
                   id="start_date"
                   name="start_date"
                   value="{{ old('start_date') }}"
                   required>
        </div>

        <div class="field">
            <label for="end_date">End Date</label>
            <input type="date"
                   id="end_date"
                   name="end_date"
                   value="{{ old('end_date') }}"
                   required>
        </div>

        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="planned"
                    @selected(old('status', 'planned') === 'planned')>
                    Planned
                </option>

                <option value="active"
                    @selected(old('status') === 'active')>
                    Active
                </option>

                <option value="completed"
                    @selected(old('status') === 'completed')>
                    Completed
                </option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button type="submit" class="button primary">
                Save Academic Year
            </button>

            <a href="{{ route('academic-years.index') }}"
               class="button secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection