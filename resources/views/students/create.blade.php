@extends('layouts.app')

@section('title', 'Register Student')

@section('content')
    <div class="page-heading">
        <div>
            <h1>Register New Student</h1>
            <p>Enter the student's information below.</p>
        </div>

        <a href="{{ route('students.index') }}" class="button secondary">
            Back to Students
        </a>
    </div>

    <div class="card">
        <form action="{{ route('students.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="student_number">Student Number *</label>
                    <input id="student_number" name="student_number"
                        type="text"
    			inputmode="numeric"
    			pattern="[0-9]+"
    			oninput="this.value = this.value.replace(/[^0-9]/g, '')"
    			value="{{ old('student_number') }}"
    			required maxlength="50">
                </div>

                <div class="field">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="active" @selected(old('status', 'active') === 'active')>
                            Active
                        </option>
                        <option value="inactive" @selected(old('status') === 'inactive')>
                            Inactive
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label for="first_name">First Name *</label>
                    <input id="first_name" name="first_name"
                        value="{{ old('first_name') }}" required maxlength="100">
                </div>

                <div class="field">
                    <label for="middle_name">Middle Name</label>
                    <input id="middle_name" name="middle_name"
                        value="{{ old('middle_name') }}" maxlength="100">
                </div>

                <div class="field">
                    <label for="last_name">Last Name *</label>
                    <input id="last_name" name="last_name"
                        value="{{ old('last_name') }}" required maxlength="100">
                </div>

                <div class="field">
                    <label for="suffix">Suffix</label>
                    <input id="suffix" name="suffix"
                        value="{{ old('suffix') }}" placeholder="Jr., Sr., III" maxlength="20">
                </div>

                <div class="field">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        max="{{ date('Y-m-d', strtotime('-1 day')) }}">
                </div>

                <div class="field">
                    <label for="sex">Sex</label>
                    <select id="sex" name="sex">
                        <option value="">Select</option>
                        <option value="Male" @selected(old('sex') === 'Male')>Male</option>
                        <option value="Female" @selected(old('sex') === 'Female')>Female</option>
                        <option value="Other" @selected(old('sex') === 'Other')>Other</option>
                    </select>
                </div>

                <div class="field full-width">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"
                        maxlength="2000">{{ old('address') }}</textarea>
                </div>

                <div class="field">
                    <label for="guardian_name">Parent/Guardian Name</label>
                    <input id="guardian_name" name="guardian_name"
                        value="{{ old('guardian_name') }}" maxlength="200">
                </div>

                <div class="field">
                    <label for="guardian_phone">Guardian Contact Number</label>
                    <input type="tel" id="guardian_phone" name="guardian_phone"
                        inputmode="numeric"
    			pattern="[0-9]*"
    			oninput="this.value = this.value.replace(/[^0-9]/g, '')"
    			value="{{ old('guardian_phone') }}"
    			maxlength="30">
                </div>
            </div>

            <div style="margin-top: 25px; display: flex; gap: 10px;">
                <button type="submit" class="button primary">
                    Save Student
                </button>

                <a href="{{ route('students.index') }}" class="button secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection