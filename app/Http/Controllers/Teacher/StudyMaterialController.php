<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\StudentClass;
use App\Models\StudyMaterial;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    public function index()
    {
        return view('teacher.materials.index', [
            'materials' => StudyMaterial::where('uploaded_by', Auth::id())
                ->latest()
                ->paginate(15),
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('teacher.materials.create', [
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'max:200'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,ppt,pptx', 'max:10240'],
        ]);

        StudyMaterial::create([
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'],
            'subject_id' => $data['subject_id'],
            'uploaded_by' => Auth::id(),
            'title' => $data['title'],
            'file_path' => $request->file('file')->store('study-materials', 'public'),
        ]);

        return redirect()->route('teacher.materials.index')->with('success', 'Study material uploaded.');
    }

    public function show(StudyMaterial $material)
    {
        return view('teacher.materials.show', compact('material'));
    }

    public function edit(StudyMaterial $material)
    {
        abort_unless($material->uploaded_by === Auth::id(), 403);

        return view('teacher.materials.edit', [
            'material' => $material,
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, StudyMaterial $material)
    {
        abort_unless($material->uploaded_by === Auth::id(), 403);

        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'max:200'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,ppt,pptx', 'max:10240'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('study-materials', 'public');
        }

        $material->update($data);

        return redirect()->route('teacher.materials.index')->with('success', 'Study material updated.');
    }

    public function destroy(StudyMaterial $material)
    {
        abort_unless($material->uploaded_by === Auth::id(), 403);

        $material->delete();

        return redirect()->route('teacher.materials.index')->with('success', 'Study material deleted.');
    }
}