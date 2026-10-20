@extends('layouts.app')
@section('content')
    <div class="head">
        <div>
            <p class="mute">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
            <h1>Halo, {{ auth()->user()->name }}</h1>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="only-m">@csrf<button class="ghost">Keluar</button></form>
    </div>

    <div class="grid">
        <a href="{{ route('thesis') }}" class="card pc c-y mod">
            <div class="ic"><svg viewBox="0 0 24 24">
                    <path d="M4 4h12a3 3 0 0 1 3 3v13H7a3 3 0 0 1-3-3zM8 9h7M8 13h5" />
                </svg></div>
            <div>
                <h2>Tugas Akhir</h2>
                <p class="mute">Tahap saat ini: belum dimulai</p>
            </div>
            <div class="foot">
                <div class="bar"><i style="width:0%"></i></div>
                <p class="mute">0% selesai</p>
            </div>
        </a>
        <a href="{{ route('toefl') }}" class="card pc c-l mod">
            <div class="ic"><svg viewBox="0 0 24 24">
                    <path d="M4 20V10M10 20V4M16 20v-8M22 20H2" />
                </svg></div>
            <div>
                <h2>TOEFL Preparation</h2>
                <p class="mute">Skor terakhir: belum ada tryout</p>
            </div>
            <div class="foot">
                <div class="bar"><i style="width:0%"></i></div>
                <p class="mute">0% menuju target</p>
            </div>
        </a>
        <a href="{{ route('career') }}" class="card pc c-m mod">
            <div class="ic"><svg viewBox="0 0 24 24">
                    <path d="M3 8h18v12H3zM8 8V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v3M3 13h18" />
                </svg></div>
            <div>
                <h2>Career Prep</h2>
                <p class="mute">Lamaran aktif: 0</p>
            </div>
            <div class="foot">
                <div class="bar"><i style="width:0%"></i></div>
                <p class="mute">0% checklist siap</p>
            </div>
        </a>
    </div>
@endsection