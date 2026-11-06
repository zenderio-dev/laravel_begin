<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/submissions/create', [SubmissionController::class, 'create'])->name('submissions.create');
Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
Route::get('/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
