<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Section;
use App\Models\StudentClass;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        return view('admin.notices.index', [
            'notices' => Notice::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.notices.create', [
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'max:200'],
            'body' => ['required'],
            'audience' => ['required', 'in:all,teachers,students,parents,class'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);

        $data['published_by'] = auth()->id();

        Notice::create($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice created.');
    }

    public function show(Notice $notice)
    {
        return view('admin.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', [
            'notice' => $notice,
            'classes' => StudentClass::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Notice $notice)
    {
        $notice->update($request->validate([
            'title' => ['required', 'max:200'],
            'body' => ['required'],
            'audience' => ['required', 'in:all,teachers,students,parents,class'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]));

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted.');
    }
}