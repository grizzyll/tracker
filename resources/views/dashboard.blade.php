@extends('layouts.app')
@section('content')
<div class="head">
  <div>
    <p class="mute">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
    <h1>Halo, {{ auth()->user()->name }}.</h1>
    <p class="mute">Langkah kecil, peluang besar.</p>
  </div>
  <div style="text-align:right">
    <span class="hand">Better English,<br>Bigger Dreams.</span>
    <form method="POST" action="{{ route('logout') }}" class="only-m" style="margin-top:8px">@csrf<button class="ghost">Keluar</button></form>
  </div>
</div>

<div class="mods">
  <a href="{{ route('thesis') }}" class="card pc c-r deco d1 mod">
    <span class="lbl-t">TUGAS AKHIR</span>
    <h2>{{ $current ? $current->name : ($total ? 'Semua tahap ACC' : 'Belum dimulai') }}</h2>
    <p class="mute">{{ $done }} dari {{ $total }} tahap ACC @if ($overdue)· <b>{{ $overdue }} lewat tenggat</b>@endif</p>
    <div class="foot">
      <div class="bar"><i @style(['width' => $percent . '%', 'background' => '#fff'])></i></div>
      <p class="mute">{{ $percent }}% progres</p>
    </div>
  </a>

  <a href="{{ route('toefl') }}" class="card pc c-y deco d2 mod">
    <span class="lbl-t">TOEFL PREP</span>
    <h2>Belum ada tryout</h2>
    <p class="mute">Modul sedang dibangun</p>
    <div class="foot">
      <div class="bar"><i style="width:0"></i></div>
      <p class="mute">0% menuju target</p>
    </div>
  </a>

  <a href="{{ route('career') }}" class="card pc c-bl deco d3 mod">
    <span class="lbl-t">CAREER PREP</span>
    <h2>Belum ada lamaran</h2>
    <p class="mute">Modul sedang dibangun</p>
    <div class="foot">
      <div class="bar"><i style="width:0;background:#fff"></i></div>
      <p class="mute">0% checklist siap</p>
    </div>
  </a>
</div>

<div class="dash">
  <div class="card">
    <div style="display:flex;justify-content:space-between">
      <h2>Tahapan berikutnya</h2>
      <a href="{{ route('thesis') }}" class="mute">Lihat semua →</a>
    </div>
    @forelse ($upcoming as $s)
      <div class="row2">
        <div class="n">
          <b>{{ $s->name }}</b>
          @if ($s->target_date)<p class="mute">{{ $s->target_date->format('d/m/Y') }}</p>@endif
        </div>
        <span class="chip ch-{{ $s->status }}">{{ ucfirst($s->status) }}</span>
      </div>
    @empty
      <p class="mute" style="margin-top:10px">{{ $total ? 'Tidak ada tahap tersisa.' : 'Belum ada tahapan.' }}</p>
    @endforelse
  </div>

  <div class="card pc c-k deco d2 week">
    <span class="sticky">kamu pasti bisa!</span>
    <span class="lbl-t">TARGET TERDEKAT</span>
    @if ($nextDeadline)
      <h2>{{ $nextDeadline->target_date->locale('id')->translatedFormat('j F Y') }}</h2>
      <p class="{{ $nextDeadline->target_date->isPast() ? 'over' : 'mute' }}">{{ $nextDeadline->name }}{{ $nextDeadline->target_date->isPast() ? ' (lewat tenggat)' : '' }}</p>
    @else
      <h2>Belum ada tenggat</h2>
      <p class="mute">Pasang tanggal target di tahap aktifmu.</p>
    @endif
  </div>
</div>
@endsection