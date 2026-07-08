<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentClass;
use App\Models\StudyMaterial;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        return view('teacher.study-materials.index', [
            'materials' => StudyMaterial::where('teacher_id', $teacher->id)
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create()
    {
        return view('teacher.study-materials.create', [
            'classes' => StudentClass::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $teacher = Auth::user()->teacher;

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'max:200'],
            'description' => ['nullable'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,ppt,pptx', 'max:10240'],
        ]);

        StudyMaterial::create([
            'teacher_id' => $teacher->id,
            'class_id' => $data['class_id'],
            'subject_id' => $data['subject_id'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'file_path' => $request->file('file')->store('study-materials', 'public'),
        ]);

        return redirect()->route('teacher.study-materials.index')->with('success', 'Study material uploaded.');
    }

    public function show(StudyMaterial $studyMaterial)
    {
        return view('teacher.study-materials.show', compact('studyMaterial'));
    }

    public function edit(StudyMaterial $studyMaterial)
    {
        abort_unless($studyMaterial->teacher_id === Auth::user()->teacher->id, 403);

        return view('teacher.study-materials.edit', [
            'studyMaterial' => $studyMaterial,
            'classes' => StudentClass::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, StudyMaterial $studyMaterial)
    {
        abort_unless($studyMaterial->teacher_id === Auth::user()->teacher->id, 403);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'max:200'],
            'description' => ['nullable'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,ppt,pptx', 'max:10240'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('study-materials', 'public');
        }

        $studyMaterial->update($data);

        return redirect()->route('teacher.study-materials.index')->with('success', 'Study material updated.');
    }

    public function destroy(StudyMaterial $studyMaterial)
    {
        abort_unless($studyMaterial->teacher_id === Auth::user()->teacher->id, 403);

        $studyMaterial->delete();

        return redirect()->route('teacher.study-materials.index')->with('success', 'Study material deleted.');
    }
}