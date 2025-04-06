<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title')</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        midnight: '#0f172a',
                        gold: '#facc15',
                        blush: '#fef9c3',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>

    <!-- Font -->
    <link href="https://fonts.bunny.net/css?family=inter:400,600&display=swap" rel="stylesheet" />
</head>
<body class="bg-midnight text-white font-sans flex items-center justify-center min-h-screen p-6">
    <div class="text-center max-w-lg">
        <h1 class="text-4xl sm:text-5xl font-semibold text-gold mb-4 leading-tight">
            @yield('code')
        </h1>
        <p class="text-lg text-gray-300 mb-6">
            @yield('message')
        </p>
        <a href="{{ url('/') }}" class="inline-block px-6 py-2 text-sm font-medium text-midnight bg-gold rounded-full hover:bg-white transition">
            Balik ke Beranda
        </a>
    </div>
</body>
</html>
