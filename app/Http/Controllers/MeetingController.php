<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::latest()->get();
        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'notulis') {
            abort(403);
        }
        return view('meetings.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'notulis') {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'topik' => 'nullable|string',
            'partisipan' => 'nullable|string',
        ]);

        Meeting::create([
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
            'jenis' => $request->jenis,
            'topik' => $request->topik,
            'partisipan' => $request->partisipan,
            'status' => 'scheduled',
            'created_by' => auth()->id(),
            'notulen' => auth()->id(),
        ]);
        return redirect()->route('meetings.index');
            with('success', 'Rapat Berhasil Dibuat');
    }


    public function show(Meeting $meeting)
    {
        $meeting->load([
            'questions.user',
            'questions.replies.user'
        ]);
        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $this->authorize('update', $meeting);
        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'lokasi' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'topik' => 'nullable|string',
            'partisipan' => 'nullable|string',
            'notulensi' => 'nullable|string',
            'status' => 'required|in:scheduled,completed'
        ]);

        $meeting->update($request->all());

        return redirect()->route('meetings.index')
            ->with('success', 'Rapat berhasil diupdate');
    }
}
