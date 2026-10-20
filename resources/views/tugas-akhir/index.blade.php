@extends('layouts.app')
@section('title', 'Tugas Akhir')
@section('content')
@php $labels = ['belum' => 'Belum', 'berjalan' => 'Berjalan', 'revisi' => 'Revisi', 'acc' => 'ACC']; @endphp

<div class="head">
  <div><p class="mute">Modul</p><h1>Tugas Akhir</h1><p class="mute">Satu tahap, satu tanggal, satu langkah.</p></div>
  <span class="hand">Better English,<br>Bigger Dreams.</span>
</div>

@if (session('ok'))<p class="flash" role="status" style="margin-top:16px">{{ session('ok') }}</p>@endif
@if ($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif

<div class="t-hero">
  <div class="card pc c-r deco d1 hero">
    <div>
      <span class="lbl-t">TUGAS AKHIR</span>
      <h2>{{ $current ? $current->name : ($total ? 'Semua tahap ACC' : 'Belum dimulai') }}</h2>
      <p class="mute">{{ $done }} dari {{ $total }} tahap ACC @if ($overdue)· <b>{{ $overdue }} lewat tenggat</b>@endif</p>
      @if ($thesis->title)<p class="mute" style="margin-top:6px">“{{ $thesis->title }}”</p>@endif
    </div>
    <svg class="ring" viewBox="0 0 120 120" role="img" aria-label="Progres tugas akhir {{ $percent }} persen">
      <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="12"/>
      <circle cx="60" cy="60" r="52" fill="none" stroke="#fff" stroke-width="12" stroke-linecap="round" stroke-dasharray="326.7" stroke-dashoffset="{{ 326.7 * (1 - $percent / 100) }}" transform="rotate(-90 60 60)"/>
      <text x="60" y="66" text-anchor="middle" font-size="22" font-weight="700" fill="#fff" font-family="Poppins">{{ $percent }}%</text>
    </svg>
  </div>

  <div class="card pc c-y deco d2 week">
    <span class="sticky">pelan tapi pasti!</span>
    <span class="lbl-t">TENGGAT TERDEKAT</span>
    @if ($nextDeadline)
      <h2>{{ $nextDeadline->target_date->locale('id')->translatedFormat('j F Y') }}</h2>
      <p class="mute">{{ $nextDeadline->name }}@if ($nextDeadline->target_date->isPast()) · <b>lewat tenggat</b>@endif</p>
    @else
      <h2>Belum ada tenggat</h2>
      <p class="mute">Pasang tanggal target di tahap aktifmu.</p>
    @endif
  </div>
</div>

<div class="t-grid">
  <div class="stack">
    <div class="card">
      <h2>Tahapan</h2>
      @foreach ($stages as $s)
        <div class="stage">
          <span class="dot st-{{ $s->status }}" aria-hidden="true">{{ $s->status === 'acc' ? '✓' : $loop->iteration }}</span>
          <div><b>{{ $s->name }}</b>@if ($s->done_at)<span class="mute"> · ACC {{ $s->done_at->format('d/m/Y') }}</span>@endif</div>
          <form method="POST" action="{{ route('thesis.stage.destroy', $s) }}" onsubmit="return confirm('Hapus tahap ini?')">
            @csrf @method('DELETE')<button class="x" aria-label="Hapus {{ $s->name }}">✕</button>
          </form>
          <form method="POST" action="{{ route('thesis.stage.update', $s) }}" class="ctl">
            @csrf @method('PATCH')
            <select name="status" aria-label="Status {{ $s->name }}" onchange="this.form.requestSubmit()">
              @foreach ($labels as $v => $l)<option value="{{ $v }}" @selected($s->status === $v)>{{ $l }}</option>@endforeach
            </select>
            <input type="date" name="target_date" value="{{ optional($s->target_date)->format('Y-m-d') }}" aria-label="Target {{ $s->name }}" onchange="this.form.requestSubmit()">
          </form>
        </div>
      @endforeach
      <form method="POST" action="{{ route('thesis.stage.store') }}" style="display:flex;gap:8px;margin-top:14px">
        @csrf
        <input name="name" placeholder="Tambah tahap baru" aria-label="Nama tahap baru" required maxlength="120">
        <button class="btn">Tambah</button>
      </form>
    </div>

    <div class="card">
      <h2>Riwayat bimbingan</h2>
      @forelse ($logs as $l)
        <div class="log {{ $l->is_done ? 'done' : '' }}">
          <div style="display:flex;justify-content:space-between;gap:12px">
            <b>{{ $l->held_on->locale('id')->translatedFormat('j F Y') }}</b>
            <form method="POST" action="{{ route('thesis.log.destroy', $l) }}" onsubmit="return confirm('Hapus catatan ini?')">@csrf @method('DELETE')<button class="x" aria-label="Hapus catatan">✕</button></form>
          </div>
          <p class="t" style="white-space:pre-line">{{ $l->summary }}</p>
          @if ($l->follow_up)
            <form method="POST" action="{{ route('thesis.log.toggle', $l) }}" style="margin-top:6px">
              @csrf @method('PATCH')
              <input type="hidden" name="is_done" value="0">
              <label style="display:flex;gap:8px;align-items:flex-start;margin:0;color:inherit;font-size:1rem">
                <input type="checkbox" name="is_done" value="1" @checked($l->is_done) onchange="this.form.requestSubmit()" style="margin-top:3px;accent-color:var(--r)">
                <span class="t" style="white-space:pre-line">{{ $l->follow_up }}</span>
              </label>
            </form>
          @endif
        </div>
      @empty
        <p class="mute" style="margin-top:10px">Belum ada catatan bimbingan. Catat setiap bimbingan, jangan mengandalkan ingatan.</p>
      @endforelse
    </div>
  </div>

  <div class="stack">
    <div class="card pc c-k kraft deco d2">
      <h2>Judul dan pembimbing</h2>
      <form method="POST" action="{{ route('thesis.info') }}">
        @csrf @method('PUT')
        <label for="title">Judul</label>
        <input id="title" name="title" value="{{ old('title', $thesis->title) }}" maxlength="255" placeholder="Belum ada judul">
        <label for="advisor">Dosen pembimbing</label>
        <input id="advisor" name="advisor" value="{{ old('advisor', $thesis->advisor) }}" maxlength="120">
        <button class="btn" style="margin-top:14px">Simpan</button>
      </form>
    </div>

    <div class="card">
      <h2>Catat bimbingan</h2>
      <form method="POST" action="{{ route('thesis.log.store') }}">
        @csrf
        <label for="held_on">Tanggal</label>
        <input id="held_on" type="date" name="held_on" value="{{ now()->toDateString() }}" required>
        <label for="summary">Arahan dosen</label>
        <textarea id="summary" name="summary" required placeholder="Apa yang dikatakan dosen?"></textarea>
        <label for="follow_up">Tindak lanjut</label>
        <textarea id="follow_up" name="follow_up" placeholder="Apa yang harus kamu kerjakan sebelum bimbingan berikutnya?"></textarea>
        <button class="btn" style="margin-top:12px">Simpan catatan</button>
      </form>
    </div>
  </div>
</div>
@endsection