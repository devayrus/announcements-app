<!DOCTYPE html>
<html lang="id">
@php
    $setting = \App\Models\Setting::first();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->brand_name ?? 'Sistem Pengumuman Sekolah' }}</title>
    @if($setting && $setting->favicon)
        <link rel="icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --colors-canvas: #fffaf0;
            --colors-primary: #0f172a;
            --colors-surface-soft: #faf5e8;
            --colors-surface-card: #f5f0e0;
            --colors-ink: #0f172a;
            --colors-body: #334155;
            --colors-muted: #64748b;
            --colors-brand-pink: #ff4d8b;
            --colors-brand-teal: #1e293b;
            --colors-brand-lavender: #b8a4ed;
            --colors-brand-peach: #ffb084;
            --colors-brand-ochre: #e8b94a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--colors-canvas);
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(var(--colors-muted) 0.5px, transparent 0.5px);
            background-size: 32px 32px;
            opacity: 0.15;
            pointer-events: none;
            z-index: -1;
        }

        .bg-blobs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -2;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            filter: blur(80px);
            opacity: 0.4;
            border-radius: 50%;
            animation: float 20s infinite alternate ease-in-out;
        }

        .blob-1 {
            width: 500px;
            height: 500px;
            background: var(--colors-brand-lavender);
            top: -100px;
            right: -100px;
        }

        .blob-2 {
            width: 400px;
            height: 400px;
            background: var(--colors-brand-peach);
            bottom: -50px;
            left: -50px;
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 100px) scale(1.1); }
        }

        .grain-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("https://grainy-gradients.vercel.app/noise.svg");
            opacity: 0.05;
            pointer-events: none;
            z-index: 100;
        }

        .font-display {
            font-weight: 500;
            letter-spacing: -0.05em;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes scaleUp {
            from { transform: scale(0.98); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .animate-scale-up { animation: scaleUp 0.5s ease-out forwards; }

        .btn-primary {
            background-color: var(--colors-primary);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .card-clay {
            border-radius: 24px;
            padding: 32px;
            transition: all 0.3s ease;
        }

        .clay-lavender {
            background-color: var(--colors-brand-lavender);
        }

        .clay-teal {
            background-color: var(--colors-brand-teal);
            color: white;
        }

        .clay-pink {
            background-color: var(--colors-brand-pink);
            color: white;
        }

        .clay-peach {
            background-color: var(--colors-brand-peach);
        }

        .clay-ochre {
            background-color: var(--colors-brand-ochre);
        }

        .clay-cream {
            background-color: var(--colors-surface-card);
        }

        .clay-success {
            background-color: #22c55e;
            color: white;
        }

        .clay-error {
            background-color: #ef4444;
            color: white;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>
    <div class="grain-overlay"></div>
    
    <!-- Top Nav -->
    <nav class="h-16 border-b border-black/5 sticky top-0 bg-[#fffaf0]/80 backdrop-blur-md z-50">
        <div class="container mx-auto px-6 md:px-12 h-full flex items-center">
            <div class="flex items-center gap-2">
                @if($setting && $setting->brand_logo)
                    <img src="{{ asset('storage/' . $setting->brand_logo) }}" alt="Logo" class="h-8 w-auto rounded-lg">
                @else
                    <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                        <span class="text-white font-black text-xl leading-none">S</span>
                    </div>
                @endif
                <span class="font-display text-xl tracking-tight">{{ $setting->brand_name ?? 'SMA Negeri 15 Bandung' }}</span>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-6 md:px-12 py-12">
        @yield('content')
    </main>

    <footer class="border-t border-black/5 py-12 mt-12">
        <div class="container mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2 opacity-40">
                @if($setting && $setting->brand_logo)
                    <img src="{{ asset('storage/' . $setting->brand_logo) }}" alt="Logo" class="h-5 w-auto grayscale rounded-md">
                @else
                    <div class="w-5 h-5 bg-black rounded-md flex items-center justify-center text-[10px]">
                        <span class="text-white font-black">S</span>
                    </div>
                @endif
                <span class="font-display text-sm tracking-tight">{{ $setting->brand_name ?? 'SMA Negeri 15 Bandung' }}</span>
            </div>
            <div class="text-[#9a9a9a] text-[13px] font-medium">
                &copy; {{ date('Y') }}. Made with ❤️ by IT SMA Negeri 15 Bandung.
            </div>
        </div>
    </footer>
</body>

</html>
