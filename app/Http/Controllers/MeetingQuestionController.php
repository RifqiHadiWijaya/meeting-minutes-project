<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingQuestion;
use Illuminate\Http\Request;

class MeetingQuestionController extends Controller
{
    public function store(Request $request, Meeting $meeting)
    {
        if (auth()->user()->role !== 'viewer') {
            abort(403);
        }

        $request->validate([
            'isi' => 'required|string'
        ]);

        MeetingQuestion::create([
            'meeting_id' => $meeting->id,
            'user_id' => auth()->id(),
            'isi' => $request->isi,
            'parent_id' => null
        ]);

        return back();
    }

    public function reply(Request $request, MeetingQuestion $question)
    {
        if (auth()->user()->role !== 'notulis') {
            abort(403);
        }

        $request->validate([
            'isi' => 'required|string'
        ]);

        MeetingQuestion::create([
            'meeting_id' => $question->meeting_id,
            'user_id' => auth()->id(),
            'isi' => $request->isi,
            'parent_id' => $question->id
        ]);

        return back();
    }
}
