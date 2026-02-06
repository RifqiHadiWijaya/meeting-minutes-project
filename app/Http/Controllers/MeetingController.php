<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Facades\Auth;


class MeetingController extends Controller
{
    public function index() {
        $meetings = Meeting::latest()->get();
        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        if (auth()->user()->role === 'viewer') {
            abort(403);
        }

        return view('meetings.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'viewer') {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required',
            'participants' => 'required',
        ]);

        Meeting::create([
            'title' => $request->title,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'participants' => $request->participants,
            'created_by' => auth()->id(),
            'status' => 'belum_dilaksanakan',
        ]);

        return redirect()->route('meetings.index')
                        ->with('success', 'Rapat berhasil ditambahkan');
    }

    public function edit(Meeting $meeting)
    {
        if (auth()->user()->role === 'viewer' || $meeting->created_by !== auth()->id()) {
            abort(403);
        }

        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        if (auth()->user()->role === 'viewer' || $meeting->created_by !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'agenda' => 'required',
            'discussion' => 'required',
        ]);

        $meeting->update([
            'agenda' => $request->agenda,
            'discussion' => $request->discussion,
            'status' => 'sudah_dilaksanakan',
        ]);

        return redirect()->route('meetings.index')
            ->with('success', 'Notulensi berhasil disimpan');
    }
}

