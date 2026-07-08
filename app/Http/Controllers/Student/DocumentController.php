<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        return view('student.documents.index', [
            'documents' => StudentDocument::where('student_id', $student->id)
                ->latest()
                ->paginate(15),
        ]);
    }

    public function create()
    {
        return view('student.documents.create');
    }

    public function store(Request $request)
    {
        $student = Auth::user()->student;

        $data = $request->validate([
            'document_type' => ['required', 'max:100'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:4096'],
        ]);

        StudentDocument::create([
            'student_id' => $student->id,
            'document_type' => $data['document_type'],
            'file_path' => $request->file('file')->store('student-documents', 'public'),
        ]);

        return redirect()->route('student.documents.index')->with('success', 'Document uploaded.');
    }

    public function show(StudentDocument $document)
    {
        abort_unless($document->student_id === Auth::user()->student->id, 403);

        return view('student.documents.show', compact('document'));
    }
}