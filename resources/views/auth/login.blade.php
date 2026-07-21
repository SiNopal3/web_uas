<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Laravel</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ time() }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN with Forms Plugin -->
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
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
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <svg viewBox="3 2 56 52" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" class="w-20 h-20 text-black mx-auto">
                    <!-- Left tall pillar & base -->
                    <path d="M19.5 4 L6 11.8 L19.5 19.6 L33 11.8 Z" />
                    <path d="M6 11.8 V40.2 L24 50.6 V35 L19.5 32.4 V19.6" />
                    <path d="M24 50.6 L47.5 37 V27.2 L33.5 19.1 L24 24.6 V35" />
                    <!-- Floating cube right above base -->
                    <path d="M46.5 10 L37.5 15.2 L46.5 20.4 L55.5 15.2 Z" />
                    <path d="M37.5 15.2 V25.6 L46.5 30.8 V20.4" />
                    <path d="M55.5 15.2 V25.6 L46.5 30.8" />
                </svg>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @if ($errors->any())
                <div class="mb-4 font-medium text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <!-- Email / Username -->
                <div>
                    <label for="username" class="block font-medium text-sm text-gray-700">
                        Email
                    </label>
                    <input id="username" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">
                        Password
                    </label>
                    <input id="password" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ url('/register') }}">
                        Need an account? Register
                    </a>

                    <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Bersihkan seluruh pilihan negara sebelumnya ketika mengakses halaman login agar sehabis login pengguna/admin harus memilih negara dari awal
        Object.keys(localStorage).forEach(k => {
            if (k.startsWith('antigravity_selected_') || k.startsWith('selected_country_')) {
                localStorage.removeItem(k);
            }
        });
        sessionStorage.clear();
    </script>
</body>
</html>
