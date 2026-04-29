@extends('layouts.app')

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
        <div class="card-clay {{ $participant->keterangan == 'LULUS' ? 'clay-success' : 'clay-error' }} shadow-xl relative overflow-hidden animate-scale-up">
            <!-- Decorative circle -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 mb-6">
                        @if($participant->keterangan == 'LULUS')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <h1 class="text-4xl md:text-6xl font-display leading-none mb-4">
                        @if($participant->keterangan == 'LULUS')
                            {{ $announcement->judul_lulus ?: 'Selamat!' }}
                        @else
                            {{ $announcement->judul_tidak_lulus ?: 'Informasi Hasil' }}
                        @endif
                    </h1>
                    <p class="text-lg text-white/80 font-medium leading-relaxed">
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
                
                <div class="bg-white rounded-3xl p-8 text-[#0a0a0a] shadow-lg">
                    <div class="space-y-6">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#9a9a9a] tracking-[2px] block mb-1">Nama Lengkap</span>
                            <span class="text-xl font-bold">{{ $participant->nama }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-[#9a9a9a] tracking-[2px] block mb-1">NISN</span>
                                <span class="text-lg font-bold tracking-widest">{{ $participant->nisn }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-[#9a9a9a] tracking-[2px] block mb-1">Kelas</span>
                                <span class="text-lg font-bold">{{ $participant->kelas }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-10 pt-8 border-t border-[#e5e5e5]">
                        <button onclick="window.print()" class="btn-primary w-full flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Cetak Bukti Hasil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card-clay clay-ochre text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white mb-8 shadow-sm">
                <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-display mb-4">NISN Tidak Ditemukan</h2>
            <p class="text-[#3a3a3a] mb-10 max-w-sm mx-auto">
                Maaf, data dengan NISN tersebut tidak terdaftar dalam pengumuman ini. Silakan periksa kembali nomor NISN Anda.
            </p>
            <a href="{{ route('home') }}" class="btn-primary inline-block">
                Coba Lagi
            </a>
        </div>
    @endif
</div>

<style>
    @media print {
        nav, footer, .mb-12, .btn-primary { display: none !important; }
        body { background: white !important; }
        .card-clay { border: none !important; box-shadow: none !important; padding: 0 !important; }
        .clay-teal, .clay-pink { background: white !important; color: black !important; }
        .clay-teal *, .clay-pink * { color: black !important; }
        .bg-white { border: 1px solid #eee !important; }
    }
</style>
@endsection
