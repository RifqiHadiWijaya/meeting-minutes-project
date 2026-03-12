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

        $question = MeetingQuestion::create([
            'meeting_id' => $meeting->id,
            'user_id' => auth()->id(),
            'user_name'  => auth()->user()->name, // ← snapshot nama penanya
            'isi' => $request->isi,
            'parent_id' => null
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'question' => [
                    'id'               => $question->id,
                    'isi'              => $question->isi,
                    'user_name'        => $question->user_name,
                    'created_at_human' => $question->created_at->format('d M Y, H:i'),
                ]
            ], 201);
        }

        return back()->with('success', 'Pertanyaan berhasil dikirim.');
    }

    public function reply(Request $request, MeetingQuestion $question)
    {
        if (auth()->user()->role !== 'notulis') {
            abort(403);
        }

        $request->validate([
            'isi' => 'required|string'
        ]);

        $reply = MeetingQuestion::create([
            'meeting_id' => $question->meeting_id,
            'user_id' => auth()->id(),
            'user_name'  => auth()->user()->name, // ← snapshot nama penjawab
            'isi' => $request->isi,
            'parent_id' => $question->id
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'reply' => [
                    'id'               => $reply->id,
                    'isi'              => $reply->isi,
                    'user_name'        => $reply->user_name,
                    'created_at_human' => $reply->created_at->format('d M Y, H:i'),
                ]
            ], 201);
        }

        return back()->with('success', 'Jawaban berhasil dikirim.');
    }
}
