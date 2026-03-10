<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk kalender
        $events = Meeting::select('id', 'judul', 'tanggal', 'status')
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

        // 5 rapat terakhir
        $recentMeetings = Meeting::with('creator')
            ->select('id', 'judul', 'tanggal', 'waktu', 'lokasi', 'status', 'jenis', 'created_by')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        // Statistik ringkasan
        $totalMeetings    = Meeting::count();
        $completedCount   = Meeting::where('status', 'completed')->count();
        $scheduledCount   = Meeting::where('status', 'scheduled')->count();

        return view('dashboard', [
            'events'         => $events,
            'recentMeetings' => $recentMeetings,
            'totalMeetings'  => $totalMeetings,
            'completedCount' => $completedCount,
            'scheduledCount' => $scheduledCount,
        ]);
    }
}
