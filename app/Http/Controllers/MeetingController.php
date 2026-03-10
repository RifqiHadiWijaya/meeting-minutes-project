<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        /**
         * Muat semua data sekaligus agar filter Status, Jenis,
         * Sorting, dan Search dapat bekerja di sisi klien (JavaScript)
         * tanpa reload halaman.
         *
         * Tetap gunakan paginate(50) sebagai batas aman jika data banyak.
         * Naikkan angka ini jika diperlukan.
         */
        $meetings = Meeting::with(['creator'])
            ->withCount('questions')
            ->latest()           // default: terbaru dulu (bisa di-override oleh JS sort)
            ->paginate(50)
            ->withQueryString();

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
            'jenis' => 'required|string|max:255',
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
            'creator_name' => auth()->user()->name, // ← snapshot nama pembuat
            'notulen' => auth()->id(),
            'notulen_name' => auth()->user()->name, // ← snapshot nama notulis
        ]);
        return redirect()->route('meetings.index')->with('success', 'Rapat Berhasil Dibuat');
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

        $meeting->update([
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
            'jenis' => $request->jenis,
            'topik' => $request->topik,
            'partisipan' => $request->partisipan,
            'notulensi' => $request->notulensi,
            'status' => $request->status,
        ]);

        return redirect()->route('meetings.index')
            ->with('success', 'Rapat berhasil diupdate');
    }

    public function exportPdf(Meeting $meeting)
    {
        $pdf = Pdf::loadView('meetings.pdf', compact('meeting'));

        return $pdf->download('notulensi-'.$meeting->judul.'.pdf');
    }
}
