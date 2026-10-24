<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicYearController extends Controller
{
    // Display all academic years.
    public function index()
    {
        $academicYears = AcademicYear::withCount('terms')
            ->orderByDesc('start_date')
            ->get();

        return view('academic-years.index', compact('academicYears'));
    }

    // Show the form for creating an academic year.
    public function create()
    {
        return view('academic-years.create');
    }

    // Save a new academic year.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:academic_years,name',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'required',
                Rule::in(['planned', 'active', 'completed']),
            ],
        ]);

        AcademicYear::create($validated);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic year created successfully.');
    }

    // Show the form for editing an academic year.
    public function edit(string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        return view('academic-years.edit', compact('academicYear'));
    }

    // Update an existing academic year.
    public function update(Request $request, string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('academic_years', 'name')
                    ->ignore($academicYear->id),
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'required',
                Rule::in(['planned', 'active', 'completed']),
            ],
        ]);

        $academicYear->update($validated);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic year updated successfully.');
    }

    // Delete an academic year.
    public function destroy(string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        // Do not delete an academic year that has terms.
        if ($academicYear->terms()->exists()) {
            return redirect()
                ->route('academic-years.index')
                ->with(
                    'error',
                    'Cannot delete this academic year because it has associated terms.'
                );
        }

        $academicYear->delete();

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Academic year deleted successfully.');
    }
}
