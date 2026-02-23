<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data dari database menggunakan Eloquent Model
        $meetings = Meeting::select('id', 'judul', 'tanggal')
            ->where('status', 'scheduled')
            ->get();

        return view('dashboard', compact('meetings'));
    }
}
