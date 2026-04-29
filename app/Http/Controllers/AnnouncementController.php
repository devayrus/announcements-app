<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcement = Announcement::where('status', 'published')->latest()->first();

        if (!$announcement) {
            return view('home', ['announcements' => collect()]);
        }

        $now = now();
        $isOpened = $now->greaterThanOrEqualTo($announcement->tanggal_buka);

        return view('show', compact('announcement', 'isOpened'));
    }

    public function show(Announcement $announcement)
    {
        if ($announcement->status !== 'published') {
            abort(404);
        }

        $now = now();
        $isOpened = $now->greaterThanOrEqualTo($announcement->tanggal_buka);

        return view('show', compact('announcement', 'isOpened'));
    }

    public function check(Request $request, Announcement $announcement)
    {
        $request->validate([
            'nisn' => 'required|string|size:10',
        ]);

        if (now()->lessThan($announcement->tanggal_buka)) {
            return redirect()->route('announcement.show', $announcement);
        }

        $participant = $announcement->participants()->where('nisn', $request->nisn)->first();

        return view('result', compact('announcement', 'participant'));
    }
}
