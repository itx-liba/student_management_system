<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeRule;
use Illuminate\Http\Request;

class GradeRuleController extends Controller
{
    public function index()
    {
        return view('admin.grade-rules.index', [
            'gradeRules' => GradeRule::orderByDesc('min_percentage')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.grade-rules.create');
    }

    public function store(Request $request)
    {
        GradeRule::create($request->validate([
            'grade' => ['required', 'max:10'],
            'min_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'max:100'],
        ]));

        return redirect()->route('admin.grade-rules.index')->with('success', 'Grade rule created.');
    }

    public function show(GradeRule $gradeRule)
    {
        return view('admin.grade-rules.show', compact('gradeRule'));
    }

    public function edit(GradeRule $gradeRule)
    {
        return view('admin.grade-rules.edit', compact('gradeRule'));
    }

    public function update(Request $request, GradeRule $gradeRule)
    {
        $gradeRule->update($request->validate([
            'grade' => ['required', 'max:10'],
            'min_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'max_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'remarks' => ['nullable', 'max:100'],
        ]));

        return redirect()->route('admin.grade-rules.index')->with('success', 'Grade rule updated.');
    }

    public function destroy(GradeRule $gradeRule)
    {
        $gradeRule->delete();
        return redirect()->route('admin.grade-rules.index')->with('success', 'Grade rule deleted.');
    }
}