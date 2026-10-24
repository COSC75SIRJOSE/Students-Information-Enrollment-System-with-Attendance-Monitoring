<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AcademicTermController extends Controller
{
    // Display academic terms.
    public function index(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => [
                'nullable',
                'integer',
                'exists:academic_years,id',
            ],
        ]);

        $academicYears = AcademicYear::orderByDesc('start_date')
            ->get();

        $query = AcademicTerm::with('academicYear');

        if (!empty($validated['academic_year_id'])) {
            $query->where(
                'academic_year_id',
                $validated['academic_year_id']
            );
        }

        $terms = $query->orderBy('start_date')->get();

        $selectedAcademicYearId =
            $validated['academic_year_id'] ?? null;

        return view('academic-terms.index', compact(
            'terms',
            'academicYears',
            'selectedAcademicYearId'
        ));
    }

    // Show the create form.
    public function create()
    {
        $academicYears = AcademicYear::orderByDesc('start_date')
            ->get();

        return view('academic-terms.create', compact('academicYears'));
    }

    // Save a new academic term.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('academic_terms', 'name')
                    ->where(fn ($query) => $query->where(
                        'academic_year_id',
                        $request->input('academic_year_id')
                    )),
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

        AcademicTerm::create($validated);

        return redirect()
            ->route('academic-terms.index', [
                'academic_year_id' => $validated['academic_year_id'],
            ])
            ->with('success', 'Academic term created successfully.');
    }

    // Show the edit form.
    public function edit(string $id)
    {
        $academicTerm = AcademicTerm::findOrFail($id);

        $academicYears = AcademicYear::orderByDesc('start_date')
            ->get();

        return view('academic-terms.edit', compact(
            'academicTerm',
            'academicYears'
        ));
    }

    // Update an existing academic term.
    public function update(Request $request, string $id)
    {
        $academicTerm = AcademicTerm::findOrFail($id);

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('academic_terms', 'name')
                    ->where(fn ($query) => $query->where(
                        'academic_year_id',
                        $request->input('academic_year_id')
                    ))
                    ->ignore($academicTerm->id),
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

        $academicTerm->update($validated);

        return redirect()
            ->route('academic-terms.index', [
                'academic_year_id' => $validated['academic_year_id'],
            ])
            ->with('success', 'Academic term updated successfully.');
    }

    // Delete an academic term.
    public function destroy(string $id)
    {
        $academicTerm = AcademicTerm::findOrFail($id);

        $academicYearId = $academicTerm->academic_year_id;

        $academicTerm->delete();

        return redirect()
            ->route('academic-terms.index', [
                'academic_year_id' => $academicYearId,
            ])
            ->with('success', 'Academic term deleted successfully.');
    }
}
