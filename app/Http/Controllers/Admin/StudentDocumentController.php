<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\Request;

class StudentDocumentController extends Controller
{
    public function index()
    {
        return view('admin.documents.index', [
            'documents' => StudentDocument::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.documents.create', [
            'students' => Student::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'document_type' => ['required', 'max:100'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:4096'],
        ]);

        StudentDocument::create([
            'student_id' => $data['student_id'],
            'document_type' => $data['document_type'],
            'file_path' => $request->file('file')->store('student-documents', 'public'),
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded.');
    }

    public function show(StudentDocument $document)
    {
        return view('admin.documents.show', compact('document'));
    }

    public function edit(StudentDocument $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, StudentDocument $document)
    {
        $data = $request->validate([
            'document_type' => ['required', 'max:100'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:4096'],
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('student-documents', 'public');
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')->with('success', 'Document updated.');
    }

    public function destroy(StudentDocument $document)
    {
        $document->delete();
        return redirect()->route('admin.documents.index')->with('success', 'Document deleted.');
    }
}