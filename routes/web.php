<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnnouncementController;

Route::get('/', [AnnouncementController::class, 'index'])->name('home');
Route::get('/pengumuman/{announcement}', [AnnouncementController::class, 'show'])->name('announcement.show');
Route::post('/pengumuman/{announcement}/cek', [AnnouncementController::class, 'check'])->name('announcement.check');
