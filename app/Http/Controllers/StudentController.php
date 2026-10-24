<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::latest()->paginate(10);

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_number' => [
		'regex:/^[0-9]+$/',
                'required',
                'string',
                'max:50',
                'unique:students,student_number',
            ],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'sex' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'address' => ['nullable', 'string', 'max:2000'],
            'guardian_name' => ['nullable', 'string', 'max:200'],
            'guardian_phone' => ['nullable', 'string', 'max:30','regex:/^[0-9]+$/',],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Student::create($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student registered successfully.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_number' => [
		'regex:/^[0-9]+$/',
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'student_number')
                    ->ignore($student->id), 
            ],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'sex' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'address' => ['nullable', 'string', 'max:2000'],
            'guardian_name' => ['nullable', 'string', 'max:200'],
            'guardian_phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9]+$/',],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $student->update($validated);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student information updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student record deleted successfully.');
    }
}