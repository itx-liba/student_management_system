<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Section;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index()
{
    return view('teacher.notices.index', [
        'notices' => Notice::latest()->paginate(15),
        'classes' => StudentClass::orderBy('name')->get(),
        'sections' => Section::orderBy('name')->get(),
    ]);
}

    public function create()
    {
        return view('teacher.notices.create', [
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'max:200'],
            'body' => ['required'],
            'audience' => ['required', 'in:students,class'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $data['published_by'] = Auth::id();

        Notice::create($data);

        return redirect()->route('teacher.notices.index')->with('success', 'Notice created.');
    }

    public function show(Notice $notice)
    {
        return view('teacher.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        abort_unless($notice->published_by === Auth::id(), 403);

        return view('teacher.notices.edit', [
            'notice' => $notice,
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Notice $notice)
    {
        abort_unless($notice->published_by === Auth::id(), 403);

        $notice->update($request->validate([
            'title' => ['required', 'max:200'],
            'body' => ['required'],
            'audience' => ['required', 'in:students,class'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]));

        return redirect()->route('teacher.notices.index')->with('success', 'Notice updated.');
    }

    public function destroy(Notice $notice)
    {
        abort_unless($notice->published_by === Auth::id(), 403);

        $notice->delete();

        return redirect()->route('teacher.notices.index')->with('success', 'Notice deleted.');
    }
}