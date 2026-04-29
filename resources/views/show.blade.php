@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-center py-12 md:py-32">
        <!-- Left Side: Content -->
        <div class="md:col-span-7 text-left">
            <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-[#f1f5f9] text-[#1e293b] text-[13px] font-bold mb-8 animate-fade-in"
                style="animation-delay: 0.1s">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                    </path>
                </svg>
                INFORMASI KELULUSAN SISWA
            </div>
            <h1 class="text-5xl md:text-7xl font-display text-[#0f172a] leading-[1.1] mb-8 animate-fade-in"
                style="animation-delay: 0.2s">
                {{ $announcement->judul }}
            </h1>
            <p class="text-xl text-[#475569] mb-8 max-w-xl leading-relaxed animate-fade-in" style="animation-delay: 0.3s">
                {{ $announcement->deskripsi ?: 'Gunakan Nomor Induk Siswa Nasional (NISN) Anda untuk melakukan verifikasi hasil kelulusan secara resmi melalui sistem informasi sekolah.' }}
            </p>
        </div>

        <!-- Right Side: Interaction -->
        <div class="md:col-span-5 animate-scale-up" style="animation-delay: 0.5s">
            @if ($isOpened)
                <div
                    class="card-clay clay-cream border border-[#e5e5e5] w-full shadow-sm text-left hover:shadow-md transition-shadow">
                    <h3 class="text-xl font-bold mb-6">Cek Kelulusan</h3>
                    <form action="{{ route('announcement.check', $announcement) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="group">
                            <input type="text" name="nisn" required placeholder="Masukkan NISN Anda"
                                class="w-full px-4 py-4 rounded-xl bg-white border border-[#e5e5e5] focus:outline-none focus:border-black focus:ring-4 focus:ring-black/5 transition-all font-medium text-lg tracking-widest placeholder:tracking-normal">
                            @error('nisn')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="btn-primary w-full text-lg shadow-lg shadow-black/5 active:scale-[0.98]">
                            Cek Hasil Sekarang
                        </button>
                    </form>
                </div>
            @else
                <div class="card-clay clay-teal text-center shadow-sm hover:scale-[1.01]">
                    <h3 class="text-xl font-display mb-8">Akan Dibuka Dalam</h3>
                    <div id="countdown" class="grid grid-cols-2 gap-4">
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <span id="days" class="block text-3xl font-display text-black">00</span>
                            <span class="text-[10px] uppercase font-bold text-[#6a6a6a] tracking-widest">Hari</span>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <span id="hours" class="block text-3xl font-display text-black">00</span>
                            <span class="text-[10px] uppercase font-bold text-[#6a6a6a] tracking-widest">Jam</span>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <span id="minutes" class="block text-3xl font-display text-black">00</span>
                            <span class="text-[10px] uppercase font-bold text-[#6a6a6a] tracking-widest">Menit</span>
                        </div>
                        <div class="bg-white rounded-2xl p-4 shadow-sm">
                            <span id="seconds" class="block text-3xl font-display text-black">00</span>
                            <span class="text-[10px] uppercase font-bold text-[#6a6a6a] tracking-widest">Detik</span>
                        </div>
                    </div>
                    <p class="mt-8 text-sm font-medium text-[#e5e5e5]">
                        <span id="local-date">Memuat waktu...</span>
                    </p>
                </div>
            @endif
        </div>
    </div>

    @if (!$isOpened)
        <script>
            const targetDateObj = new Date("{{ $announcement->tanggal_buka->toIso8601String() }}");
            const targetDate = targetDateObj.getTime();

            // Tampilkan waktu lokal user
            const localDateStr = targetDateObj.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('local-date').innerText = localDateStr;

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = targetDate - now;

                if (distance < 0) {
                    window.location.reload();
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("days").innerText = String(days).padStart(2, '0');
                document.getElementById("hours").innerText = String(hours).padStart(2, '0');
                document.getElementById("minutes").innerText = String(minutes).padStart(2, '0');
                document.getElementById("seconds").innerText = String(seconds).padStart(2, '0');
            }

            setInterval(updateCountdown, 1000);
            updateCountdown();
        </script>
    @endif
@endsection
