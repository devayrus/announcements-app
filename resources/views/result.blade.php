@extends('layouts.app')

@php
    $setting = \App\Models\Setting::first();
@endphp

@section('content')
<div class="mb-12">
    <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-semibold text-[#6a6a6a] hover:text-black transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Beranda
    </a>
</div>

<div class="max-w-4xl mx-auto">
    @if($participant)
        <div class="card-clay {{ $participant->keterangan == 'LULUS' ? 'clay-success' : 'clay-error' }} shadow-xl relative overflow-hidden animate-scale-up print:shadow-none print:border-2 print:border-black/5 print:p-0">
            <!-- Print Header (Hanya muncul saat dicetak) -->
            <div class="hidden print:flex items-center justify-between border-b-2 border-black/10 pb-8 mb-10">
                <div class="flex items-center gap-4">
                    @if($setting && $setting->brand_logo)
                        <img src="{{ asset('storage/' . $setting->brand_logo) }}" class="h-16 w-auto">
                    @else
                        <div class="w-12 h-12 bg-black rounded-xl"></div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-black tracking-tight leading-none mb-1">{{ $setting->brand_name ?? 'SMA Negeri 15 Bandung' }}</h2>
                        <p class="text-xs font-medium opacity-50 uppercase tracking-widest">Sistem Pengumuman Hasil Evaluasi</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold uppercase tracking-tighter opacity-30 mb-1">Generated on</p>
                    <p class="text-sm font-mono font-bold">{{ date('d M Y, H:i') }}</p>
                </div>
            </div>

            <!-- Decorative circle (Hide on print) -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full print:hidden"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10 print:gap-8">
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 mb-6 print:w-12 print:h-12 print:rounded-xl">
                        @if($participant->keterangan == 'LULUS')
                            <svg class="w-8 h-8 text-white print:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-white print:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-6xl font-display leading-none mb-4 print:text-3xl print:mb-2 print:text-black">
                        @if($participant->keterangan == 'LULUS')
                            {{ $announcement->judul_lulus ?: 'Selamat!' }}
                        @else
                            {{ $announcement->judul_tidak_lulus ?: 'Informasi Hasil' }}
                        @endif
                    </h1>
                    <p class="text-lg text-white/80 font-medium leading-relaxed print:text-black print:text-sm">
                        @if($participant->keterangan == 'LULUS')
                            @if($announcement->pesan_lulus)
                                {!! str_replace('[status]', '<span class="font-black underline">'.$participant->keterangan.'</span>', $announcement->pesan_lulus) !!}
                            @else
                                Berdasarkan keputusan rapat Dewan Guru, Anda dinyatakan <span class="font-black underline">{{ $participant->keterangan }}</span> dalam evaluasi akhir tahun ajaran ini.
                            @endif
                        @else
                            @if($announcement->pesan_tidak_lulus)
                                {!! str_replace('[status]', '<span class="font-black underline">'.$participant->keterangan.'</span>', $announcement->pesan_tidak_lulus) !!}
                            @else
                                Berdasarkan keputusan rapat Dewan Guru, Anda dinyatakan <span class="font-black underline">{{ $participant->keterangan }}</span> dalam evaluasi akhir tahun ajaran ini.
                            @endif
                        @endif
                    </p>
                </div>
                
                <div class="bg-white rounded-3xl p-8 text-[#0a0a0a] shadow-lg print:shadow-none print:border-2 print:border-black/10 print:rounded-2xl print:p-6">
                    <div class="space-y-6 print:space-y-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#9a9a9a] tracking-[2px] block mb-1">Nama Lengkap</span>
                            <span class="text-xl font-bold print:text-lg">{{ $participant->nama }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-[#9a9a9a] tracking-[2px] block mb-1">NISN</span>
                                <span class="text-lg font-bold tracking-widest print:text-base">{{ $participant->nisn }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-[#9a9a9a] tracking-[2px] block mb-1">Kelas</span>
                                <span class="text-lg font-bold print:text-base">{{ $participant->kelas }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-10 pt-8 border-t border-[#e5e5e5] print:mt-6 print:pt-4">
                        <button onclick="window.print()" class="btn-primary w-full flex items-center justify-center gap-2 print:hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak Bukti Hasil
                        </button>
                        <p class="hidden print:block text-center text-[10px] font-medium text-[#9a9a9a]">Dicetak secara mandiri melalui portal pengumuman resmi.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card-clay clay-ochre text-center shadow-lg max-w-2xl mx-auto py-16 px-8 animate-scale-up">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-white mb-8 shadow-sm">
                <svg class="w-12 h-12 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h2 class="text-4xl font-display mb-6">NISN Tidak Ditemukan</h2>
            <p class="text-[#3a3a3a] text-lg mb-12 max-w-md mx-auto leading-relaxed">
                Maaf, data dengan nomor NISN tersebut tidak terdaftar dalam basis data pengumuman ini. Silakan periksa kembali nomor yang Anda masukkan dan coba lagi.
            </p>
            <a href="{{ route('home') }}" class="btn-primary inline-flex items-center gap-2 px-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Coba Lagi
            </a>
        </div>
    @endif
</div>

<style>
    @media print {
        @page { margin: 1cm; }
        nav, footer, .mb-12, .btn-primary, .grain-overlay, .bg-blobs { display: none !important; }
        body { background: white !important; }
        .card-clay { 
            border-radius: 0 !important;
            background: white !important;
            color: black !important;
        }
        .card-clay.clay-success { border-color: #22c55e !important; }
        .card-clay.clay-error { border-color: #ef4444 !important; }
        .grid { display: grid !important; }
    }
</style>
@endsection
