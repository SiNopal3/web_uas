<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Figtree', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-[#0a0a0a] text-white selection:bg-[#FF2D20] selection:text-white">
    <div class="relative min-h-screen flex flex-col items-center justify-center overflow-hidden">
        <!-- Header / Navigation di Ujung Kanan -->
        <header class="w-full flex justify-end items-center px-6 py-6 absolute top-0 right-0 z-20">
            @if (Route::has('login'))
                <nav class="-mx-3 flex flex-1 justify-end items-center space-x-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-white/80 focus:outline-none focus-visible:ring-[#FF2D20] font-semibold"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="rounded-md px-3 py-2 text-white font-semibold transition hover:text-white/80 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="rounded-md px-3 py-2 text-white font-semibold transition hover:text-white/80 focus:outline-none focus-visible:ring-[#FF2D20]"
                            >
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Card -->
        <main class="w-full max-w-5xl px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 bg-[#161618] rounded-xl border border-white/10 overflow-hidden shadow-2xl">
                <!-- Left Side Content -->
                <div class="p-8 lg:p-12 flex flex-col justify-center">
                    <h2 class="text-xl font-semibold text-white mb-2">Let's get started</h2>
                    <p class="text-sm text-zinc-400 mb-6 leading-relaxed">
                        Laravel has an incredibly rich ecosystem. We suggest starting with the following.
                    </p>

                    <div class="space-y-4 mb-8">
                        <a href="https://laravel.com/docs" target="_blank" class="flex items-center gap-2 text-sm font-semibold text-white hover:text-[#FF2D20] transition group">
                            <span class="w-2 h-2 rounded-full border border-zinc-600 group-hover:border-[#FF2D20]"></span>
                            <span>Read the Documentation <span class="text-[#FF2D20] ml-1">↗</span></span>
                        </a>
                        <a href="https://laracasts.com" target="_blank" class="flex items-center gap-2 text-sm font-semibold text-white hover:text-[#FF2D20] transition group">
                            <span class="w-2 h-2 rounded-full border border-zinc-600 group-hover:border-[#FF2D20]"></span>
                            <span>Watch video tutorials at <span class="underline decoration-[#FF2D20] underline-offset-4">Laracasts</span> <span class="text-[#FF2D20] ml-1">↗</span></span>
                        </a>
                    </div>

                    <div>
                        <a href="https://cloud.laravel.com" target="_blank" class="inline-block px-5 py-2.5 bg-white text-black font-semibold text-sm rounded-md hover:bg-zinc-200 transition shadow-sm">
                            Deploy now
                        </a>
                    </div>
                </div>

                <!-- Right Side Wireframe Laravel Graphic -->
                <div class="relative bg-[#0d0d0e] flex items-center justify-center p-6 lg:p-0 overflow-hidden min-h-[320px]">
                    <div class="absolute top-8 left-8 text-[#FF2D20] font-black text-6xl tracking-tighter opacity-90 select-none">
                        Laravel
                    </div>
                    <!-- Isometric wireframe lines matching Laravel 11 welcome screen -->
                    <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full transform scale-125 translate-y-12 translate-x-12 opacity-90">
                        <g stroke="#FF2D20" stroke-width="1.2" stroke-opacity="0.6">
                            <path d="M150 100 L50 158 L150 216 L250 158 Z" />
                            <path d="M50 158 V274 L150 332 V216" />
                            <path d="M250 158 V274 L150 332" />
                            <path d="M150 130 L80 171 L150 212 L220 171 Z" />
                            <path d="M80 171 V253 L150 294 V212" />
                            <path d="M220 171 V253 L150 294" />
                            <path d="M280 80 L210 120 L280 160 L350 120 Z" />
                            <path d="M210 120 V200 L280 240 V160" />
                            <path d="M350 120 V200 L280 240" />
                            <path d="M150 115 L65 164 L150 214 L235 164 Z" stroke-width="0.8" stroke-opacity="0.4"/>
                            <path d="M65 164 V263 L150 313 V214" stroke-width="0.8" stroke-opacity="0.4"/>
                            <path d="M235 164 V263 L150 313" stroke-width="0.8" stroke-opacity="0.4"/>
                            <path d="M280 95 L225 126 L280 158 L335 126 Z" stroke-width="0.8" stroke-opacity="0.4"/>
                            <path d="M225 126 V189 L280 221 V158" stroke-width="0.8" stroke-opacity="0.4"/>
                            <path d="M335 126 V189 L280 221" stroke-width="0.8" stroke-opacity="0.4"/>
                            <line x1="50" y1="158" x2="250" y2="274" stroke-opacity="0.3"/>
                            <line x1="250" y1="158" x2="50" y2="274" stroke-opacity="0.3"/>
                            <line x1="150" y1="100" x2="150" y2="332" stroke-opacity="0.5" stroke-width="1.5"/>
                            <line x1="280" y1="80" x2="280" y2="240" stroke-opacity="0.5" stroke-width="1.5"/>
                            <path d="M150 160 L100 189 L150 218 L200 189 Z" stroke="#FF2D20" stroke-width="2"/>
                            <path d="M280 110 L245 130 L280 150 L315 130 Z" stroke="#FF2D20" stroke-width="2"/>
                        </g>
                    </svg>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
