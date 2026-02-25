<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;

class DashboardController extends Controller
{
    public function index()
    {
        $meetings = Meeting::select('id', 'judul', 'tanggal', 'status')
            ->get()
            ->map(function ($m) {
                return [
                    'title' => $m->judul,
                    'start' => $m->tanggal,
                    'url'   => route('meetings.show', $m->id),
                    'color' => $m->status === 'completed'
                        ? '#16a34a'
                        : '#2563eb',
                ];
            });

        return view('dashboard', [
            'events' => $meetings
        ]);
    }
}
