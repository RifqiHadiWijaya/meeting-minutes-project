<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetingQuestionController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware('auth')->group(function () {

    Route::resource('meetings', MeetingController::class);

    Route::post('/meetings/{meeting}/questions',
        [MeetingQuestionController::class, 'store'])
        ->name('questions.store');

    Route::post('/questions/{question}/reply',
        [MeetingQuestionController::class, 'reply'])
        ->name('questions.reply');

    // Route PDF
    Route::get('/meetings/{meeting}/pdf',
        [MeetingController::class, 'exportPdf'])
        ->name('meetings.pdf');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});


require __DIR__.'/auth.php';
