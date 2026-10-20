@extends('layouts.app')
@section('title', 'TOEFL')
@section('content')
@php
  $secs = ['listening' => 'Listening', 'structure' => 'Structure', 'reading' => 'Reading', 'vocab' => 'Vocab', 'umum' => 'Umum'];
  $s = $t['setting'];
@endphp

<div class="head">
  <div><p class="mute">Modul</p><h1>TOEFL Preparation</h1><p class="mute">Konsisten, bukan sekadar semangat.</p></div>
  <span class="hand">Better English,<br>Bigger Dreams.</span>
</div>

@if (session('ok'))<p class="flash" role="status" style="margin-top:16px">{{ session('ok') }}</p>@endif
@if ($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif

<div class="t-hero">
  <div class="card pc c-r deco d1 hero">
    <div>
      <span class="lbl-t">TOEFL ITP</span>
      <h2>{{ $t['target'] ? 'Target skor '.$t['target'] : 'Target belum diatur' }}</h2>
      <p class="mute">{{ $t['total'] ? 'Skor terakhir '.$t['total'] : 'Belum ada tryout' }}@if (!is_null($t['daysLeft']) && $t['daysLeft'] >= 0) · {{ $t['daysLeft'] }} hari menuju tes @endif</p>
    </div>
    <svg class="ring" viewBox="0 0 120 120" role="img" aria-label="Progres {{ $t['percent'] }} persen">
      <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="12"/>
      <circle cx="60" cy="60" r="52" fill="none" stroke="#fff" stroke-width="12" stroke-linecap="round" stroke-dasharray="326.7" stroke-dashoffset="{{ 326.7 * (1 - $t['percent'] / 100) }}" transform="rotate(-90 60 60)"/>
      <text x="60" y="66" text-anchor="middle" font-size="22" font-weight="700" fill="#fff" font-family="Poppins">{{ $t['percent'] }}%</text>
    </svg>
  </div>
  <div class="card pc c-y deco d2 week">
    <span class="sticky">you got this!</span>
    <span class="lbl-t">MINGGU INI</span>
    <h2>{{ $t['weekDone'] }} / {{ $t['weekTarget'] }} target selesai</h2>
    <div class="bar"><i style="width:{{ $t['weekPct'] }}%"></i></div>
  </div>
</div>

<div class="t-grid">
  <div class="stack">
    <div class="card">
      <h2>Rencana belajar</h2>
      @forelse ($t['tasks'] as $k)
        <div class="task">
          <form method="POST" action="{{ route('toefl.task.toggle', $k) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="is_done" value="0">
            <input type="checkbox" name="is_done" value="1" aria-label="Selesai: {{ $k->title }}" onchange="this.form.requestSubmit()">
          </form>
          <div class="n"><b>{{ $k->title }}</b>
            @if ($k->due_on)<p class="{{ $k->due_on->isPast() ? 'over' : 'mute' }}">{{ $k->due_on->format('d/m/Y') }}{{ $k->due_on->isPast() ? ' (lewat)' : '' }}</p>@endif
          </div>
          <span class="chip sec-{{ $k->section }}">{{ $secs[$k->section] ?? 'Umum' }}</span>
          <form method="POST" action="{{ route('toefl.task.destroy', $k) }}" onsubmit="return confirm('Hapus target ini?')">@csrf @method('DELETE')<button class="x" aria-label="Hapus {{ $k->title }}">✕</button></form>
        </div>
      @empty
        <p class="mute" style="margin-top:10px">Belum ada target. Tambahkan satu di bawah, misalnya “25 soal Structure”.</p>
      @endforelse

      @foreach ($t['doneTasks'] as $k)
        <div class="task" style="opacity:.55">
          <form method="POST" action="{{ route('toefl.task.toggle', $k) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="is_done" value="0">
            <input type="checkbox" checked aria-label="Buka kembali: {{ $k->title }}" onchange="this.form.requestSubmit()">
          </form>
          <div class="n"><s>{{ $k->title }}</s><p class="mute">selesai {{ $k->done_at->format('d/m') }}</p></div>
        </div>
      @endforeach

      <form method="POST" action="{{ route('toefl.task.store') }}" class="addrow">
        @csrf
        <input name="title" placeholder="Target belajar baru" aria-label="Target belajar baru" required maxlength="160">
        <select name="section" aria-label="Section">@foreach ($secs as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach</select>
        <input type="date" name="due_on" aria-label="Tenggat">
        <button class="btn">Tambah</button>
      </form>
    </div>

    <div class="card pc c-k kraft deco d2">
      <h2>Catatan hari ini</h2>
      <form method="POST" action="{{ route('toefl.note') }}">
        @csrf @method('PUT')
        <label for="note">Apa satu hal yang kamu pelajari atau masih bingung hari ini?</label>
        <textarea id="note" name="body" maxlength="5000">{{ old('body', optional($t['note'])->body) }}</textarea>
        <button class="btn" style="margin-top:10px">Simpan catatan</button>
      </form>
    </div>
  </div>

  <div class="stack">
    <div class="card">
      <h2>Skill check</h2>
      <p class="mute">Skor section tryout terakhir. Structure = Structure & Written Expression.</p>
      @foreach ($t['sections'] as $x)
        <div class="skill"><span>{{ $x['label'] }}</span><div class="bar"><i style="width:{{ $x['pct'] }}%"></i></div><span>{{ $x['score'] ?? '–' }}</span></div>
      @endforeach
    </div>

    <div class="card">
      <h2>Milestones</h2>
      @foreach ($t['milestones'] as $m)
        <div class="ms {{ $m['done'] ? 'ok' : '' }}"><span class="o">{{ $m['done'] ? '✓' : '' }}</span><span>{{ $m['label'] }}</span><small>{{ $m['info'] }}</small></div>
      @endforeach
    </div>

    <div class="card">
      <h2>Catat tryout</h2>
      <form method="POST" action="{{ route('toefl.tryout.store') }}">
        @csrf
        <label for="taken_on">Tanggal</label>
        <input id="taken_on" type="date" name="taken_on" value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required>
        <div class="g3">
          <div><label for="l">Listening</label><input id="l" name="listening" type="number" inputmode="numeric" min="31" max="68" required></div>
          <div><label for="st">Structure</label><input id="st" name="structure" type="number" inputmode="numeric" min="31" max="68" required></div>
          <div><label for="r">Reading</label><input id="r" name="reading" type="number" inputmode="numeric" min="31" max="67" required></div>
        </div>
        <label for="notes">Catatan (opsional)</label>
        <input id="notes" name="notes" maxlength="2000" placeholder="Contoh: Part C Listening kehabisan waktu">
        <button class="btn" style="margin-top:12px">Simpan skor</button>
        <p class="mute" style="margin-top:8px">Skor ITP = rata-rata tiga section × 10.</p>
      </form>
    </div>

    <div class="card">
      <h2>Pengaturan target</h2>
      <form method="POST" action="{{ route('toefl.setting') }}">
        @csrf @method('PUT')
        <label for="ts">Target skor (310-677)</label>
        <input id="ts" type="number" name="target_score" min="310" max="677" value="{{ old('target_score', $s->target_score) }}">
        <label for="td">Tanggal tes</label>
        <input id="td" type="date" name="test_date" value="{{ old('test_date', optional($s->test_date)->format('Y-m-d')) }}">
        <label for="wt">Target belajar per minggu</label>
        <input id="wt" type="number" name="weekly_target" min="1" max="14" value="{{ old('weekly_target', $s->weekly_target) }}" required>
        <button class="btn" style="margin-top:12px">Simpan</button>
      </form>
    </div>
  </div>
</div>

<div class="card" style="margin-top:18px">
  <h2>Riwayat tryout</h2>
  @if ($t['tryouts']->isEmpty())
    <p class="mute" style="margin-top:10px">Belum ada tryout. Kerjakan simulasi diagnostik minggu ini, durasi asli, tanpa kamus.</p>
  @else
    <div class="box"><table class="hist">
      <tr><th>Tanggal</th><th>L</th><th>S</th><th>R</th><th>Total</th><th>Catatan</th><th></th></tr>
      @foreach ($t['tryouts'] as $r)
        <tr>
          <td>{{ $r->taken_on->format('d/m/Y') }}</td><td>{{ $r->listening }}</td><td>{{ $r->structure }}</td><td>{{ $r->reading }}</td>
          <td><b>{{ \App\Services\ToeflProgress::total($r) }}</b></td><td>{{ $r->notes }}</td>
          <td><form method="POST" action="{{ route('toefl.tryout.destroy', $r) }}" onsubmit="return confirm('Hapus tryout ini?')">@csrf @method('DELETE')<button class="x" aria-label="Hapus tryout {{ $r->taken_on->format('d/m/Y') }}">✕</button></form></td>
        </tr>
      @endforeach
    </table></div>
  @endif
</div>
@endsection