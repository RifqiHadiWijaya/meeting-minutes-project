<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetingQuestionController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
