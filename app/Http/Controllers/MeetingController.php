<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;

class MeetingController extends Controller
{
    public function index() {
        $meetings = Meeting::latest()->get();
        return view('meetings.index', compact('meetings'));
    }
}
