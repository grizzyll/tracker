<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Beranda') · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="shell @auth has-nav @endauth">
        @auth
            @php
                $items = [
                    ['dashboard', 'Beranda', 'M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z'],
                    ['thesis', 'Tugas Akhir', 'M4 4h12a3 3 0 0 1 3 3v13H7a3 3 0 0 1-3-3zM8 9h7M8 13h5'],
                    ['toefl', 'TOEFL', 'M4 20V10M10 20V4M16 20v-8M22 20H2'],
                    ['career', 'Career Prep', 'M3 8h18v12H3zM8 8V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v3M3 13h18'],
                ];
            @endphp
            <nav class="side" aria-label="Navigasi utama">
                <div class="brand">{{ config('app.name') }}</div>
                @foreach ($items as [$route, $label, $path])
                    <a href="{{ route($route) }}" aria-label="{{ $label }}" @if(request()->routeIs($route)) aria-current="page"
                    @endif>
                        <svg viewBox="0 0 24 24">
                            <path d="{{ $path }}" />
                        </svg><span class="lbl">{{ $label }}</span>
                    </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="out">@csrf<button>Keluar</button></form>
            </nav>
        @endauth
        <main class="main">@yield('content')</main>
    </div>
</body>

</html>