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

<div class="dash">
  <a href="{{ route('thesis') }}" class="card pc c-r deco d1 hero">
    <div>
      <span class="lbl-t">TUGAS AKHIR</span>
      <h2>{{ $current ? $current->name : ($total ? 'Semua tahap ACC' : 'Belum dimulai') }}</h2>
      <p class="mute">{{ $done }} dari {{ $total }} tahap ACC @if ($overdue)· <b>{{ $overdue }} lewat tenggat</b>@endif</p>
    </div>
    <svg class="ring" viewBox="0 0 120 120" role="img" aria-label="Progres tugas akhir {{ $percent }} persen">
      <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="12"/>
      <circle cx="60" cy="60" r="52" fill="none" stroke="#fff" stroke-width="12" stroke-linecap="round" stroke-dasharray="326.7" stroke-dashoffset="{{ 326.7 * (1 - $percent / 100) }}" transform="rotate(-90 60 60)"/>
      <text x="60" y="66" text-anchor="middle" font-size="22" font-weight="700" fill="#fff" font-family="Poppins">{{ $percent }}%</text>
    </svg>
  </a>

  <a href="{{ route('toefl') }}" class="card pc c-y deco d2 week">
    <span class="sticky">kamu pasti bisa!</span>
    <span class="lbl-t">TOEFL PREP</span>
    <h2>{{ $toefl['total'] ? 'Skor terakhir '.$toefl['total'] : 'Belum ada tryout' }}</h2>
    <p class="mute">{{ $toefl['target'] ? 'Target '.$toefl['target'] : 'Target belum diatur' }}@if (!is_null($toefl['daysLeft']) && $toefl['daysLeft'] >= 0) · {{ $toefl['daysLeft'] }} hari lagi @endif</p>
    <div class="bar"><i style="width:{{ $toefl['percent'] }}%"></i></div>
    <p class="mute">{{ $toefl['percent'] }}% menuju target</p>
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
          @if ($s->target_date)<p class="{{ $s->target_date->isPast() ? 'over' : 'mute' }}">{{ $s->target_date->format('d/m/Y') }}{{ $s->target_date->isPast() ? ' (lewat)' : '' }}</p>@endif
        </div>
        <span class="chip ch-{{ $s->status }}">{{ ucfirst($s->status) }}</span>
      </div>
    @empty
      <p class="mute" style="margin-top:10px">{{ $total ? 'Tidak ada tahap tersisa.' : 'Belum ada tahapan.' }}</p>
    @endforelse
  </div>

  <div class="stack">
    <a href="{{ route('career') }}" class="card pc c-bl deco d3">
      <span class="lbl-t">CAREER PREP</span>
      <h2>{{ $career['total'] ? $career['active'].' lamaran aktif' : 'Belum ada lamaran' }}</h2>
      <p class="mute">{{ $career['interview'] }} interview · {{ $career['offer'] }} offer · {{ $career['rejected'] }} ditolak</p>
      <div class="bar"><i style="width:{{ $career['percent'] }}%;background:#fff"></i></div>
      <p class="mute">Checklist siap {{ $career['percent'] }}%</p>
    </a>

    <div class="card pc c-k deco d2">
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
</div>
@endsection