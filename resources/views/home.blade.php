@extends('layouts.app')

@section('content')
<div class="text-center mb-12">
    <h1 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Daftar Pengumuman</h1>
    <p class="text-slate-500 text-lg">Pilih pengumuman untuk melihat hasil kelulusan Anda.</p>
</div>

@if($announcements->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="text-slate-300 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-slate-900">Belum ada pengumuman aktif</h3>
        <p class="text-slate-500 mt-2">Silakan cek kembali nanti.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($announcements as $announcement)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col hover:shadow-md transition-shadow">
                <h3 class="text-xl font-bold text-slate-900 mb-2 leading-tight">{{ $announcement->judul }}</h3>
                <p class="text-slate-500 line-clamp-3 mb-6 flex-grow">
                    {{ $announcement->deskripsi ?: 'Tidak ada deskripsi.' }}
                </p>
                <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        Dibuka: {{ $announcement->tanggal_buka->format('d M Y H:i') }}
                    </span>
                    <a href="{{ route('announcement.show', $announcement) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                        Lihat
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
