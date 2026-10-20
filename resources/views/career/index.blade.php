@extends('layouts.app')
@section('title', 'Career Prep')
@section('content')
@php
  $st   = ['wishlist' => 'Wishlist', 'applied' => 'Dikirim', 'interview' => 'Interview', 'offer' => 'Offer', 'rejected' => 'Ditolak', 'withdrawn' => 'Ditarik'];
  $cats = ['cv' => 'CV', 'portofolio' => 'Portofolio', 'skill' => 'Skill', 'jaringan' => 'Jaringan'];
@endphp

<div class="head">
  <div><p class="mute">Modul</p><h1>Career Prep</h1><p class="mute">Kirim lamaran, catat hasilnya, belajar dari setiap jawaban.</p></div>
  <span class="hand">Better English,<br>Bigger Dreams.</span>
</div>

@if (session('ok'))<p class="flash" role="status" style="margin-top:16px">{{ session('ok') }}</p>@endif
@if ($errors->any())<p class="err" role="alert">{{ $errors->first() }}</p>@endif

<div class="t-hero">
  <div class="card pc c-bl deco d3 hero">
    <div>
      <span class="lbl-t">CAREER PREP</span>
      <h2>Kesiapan karier {{ $c['percent'] }}%</h2>
      <p class="mute">{{ $c['itemsDone'] }} dari {{ $c['itemsTotal'] }} item checklist selesai</p>
    </div>
    <svg class="ring" viewBox="0 0 120 120" role="img" aria-label="Kesiapan {{ $c['percent'] }} persen">
      <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="12"/>
      <circle cx="60" cy="60" r="52" fill="none" stroke="#fff" stroke-width="12" stroke-linecap="round" stroke-dasharray="326.7" stroke-dashoffset="{{ 326.7 * (1 - $c['percent'] / 100) }}" transform="rotate(-90 60 60)"/>
      <text x="60" y="66" text-anchor="middle" font-size="22" font-weight="700" fill="#fff" font-family="Poppins">{{ $c['percent'] }}%</text>
    </svg>
  </div>

  <div class="card pc c-y deco d2 week">
    <span class="sticky">kirim satu lagi!</span>
    <span class="lbl-t">LAMARAN</span>
    <h2>{{ $c['active'] }} lamaran aktif</h2>
    <p class="mute">{{ $c['sent'] }} dikirim · {{ $c['interview'] }} interview · {{ $c['offer'] }} offer · {{ $c['rejected'] }} ditolak</p>
    @if ($c['upcoming']->isNotEmpty())
      @php $u = $c['upcoming']->first(); @endphp
      <p style="margin-top:8px"><b>Berikutnya:</b> {{ $u->company }}, {{ $u->next_step_on->locale('id')->translatedFormat('j F') }}</p>
    @endif
  </div>
</div>

<div class="t-grid">
  <div class="stack">
    <div class="card">
      <h2>Daftar lamaran</h2>

      @forelse ($c['apps'] as $a)
        <details class="app">
          <summary>
            <div class="n">
              <b>{{ $a->company }}</b>
              <p class="mute">{{ $a->position }}@if ($a->next_step_on) · <span class="{{ $a->next_step_on->lt(today()) && in_array($a->status, ['applied', 'interview']) ? 'over' : '' }}">{{ $a->next_step_on->format('d/m/Y') }}</span>@endif</p>
            </div>
            <span class="chip ca-{{ $a->status }}">{{ $st[$a->status] ?? $a->status }}</span>
          </summary>
          <div class="edit">
            <form method="POST" action="{{ route('career.app.update', $a) }}">
              @csrf @method('PATCH')
              <div class="addapp">
                <div><label for="co{{ $a->id }}">Perusahaan</label><input id="co{{ $a->id }}" name="company" value="{{ $a->company }}" maxlength="120" required></div>
                <div><label for="po{{ $a->id }}">Posisi</label><input id="po{{ $a->id }}" name="position" value="{{ $a->position }}" maxlength="120" required></div>
                <div><label for="st{{ $a->id }}">Status</label>
                  <select id="st{{ $a->id }}" name="status">@foreach ($st as $v => $l)<option value="{{ $v }}" @selected($a->status === $v)>{{ $l }}</option>@endforeach</select></div>
                <div><label for="ur{{ $a->id }}">Tautan lowongan</label><input id="ur{{ $a->id }}" name="url" value="{{ $a->url }}" maxlength="255" placeholder="https://"></div>
                <div><label for="ap{{ $a->id }}">Tanggal kirim</label><input id="ap{{ $a->id }}" type="date" name="applied_on" value="{{ optional($a->applied_on)->format('Y-m-d') }}"></div>
                <div><label for="nx{{ $a->id }}">Langkah berikutnya (tanggal)</label><input id="nx{{ $a->id }}" type="date" name="next_step_on" value="{{ optional($a->next_step_on)->format('Y-m-d') }}"></div>
                <div class="w"><label for="no{{ $a->id }}">Catatan</label><textarea id="no{{ $a->id }}" name="notes" maxlength="3000">{{ $a->notes }}</textarea></div>
                <div class="w"><label for="le{{ $a->id }}">Pelajaran dari proses ini {{ $a->status === 'rejected' ? '(wajib diisi)' : '' }}</label><textarea id="le{{ $a->id }}" name="lesson" maxlength="3000" placeholder="Apa yang kurang? Apa yang akan kamu ubah di lamaran berikutnya?">{{ $a->lesson }}</textarea></div>
              </div>
              <div style="display:flex;gap:8px;margin-top:12px;align-items:center">
                <button class="btn">Simpan</button>
                @if ($a->url)<a class="mute" href="{{ $a->url }}" target="_blank" rel="noopener noreferrer">Buka lowongan ↗</a>@endif
              </div>
            </form>
            <form method="POST" action="{{ route('career.app.destroy', $a) }}" onsubmit="return confirm('Hapus lamaran ini?')" style="margin-top:6px">
              @csrf @method('DELETE')<button class="x">Hapus lamaran</button>
            </form>
          </div>
        </details>
      @empty
        <p class="mute" style="margin-top:10px">Belum ada lamaran. Mulai dari wishlist: catat 5 perusahaan yang kamu incar.</p>
      @endforelse

      <h2 style="margin-top:22px">Tambah lamaran</h2>
      <form method="POST" action="{{ route('career.app.store') }}">
        @csrf
        <div class="addapp">
          <div><label for="nco">Perusahaan</label><input id="nco" name="company" maxlength="120" required></div>
          <div><label for="npo">Posisi</label><input id="npo" name="position" maxlength="120" required></div>
          <div><label for="nst">Status</label><select id="nst" name="status">@foreach ($st as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach</select></div>
          <div><label for="nur">Tautan lowongan</label><input id="nur" name="url" maxlength="255" placeholder="https://"></div>
          <div><label for="nap">Tanggal kirim</label><input id="nap" type="date" name="applied_on"></div>
          <div><label for="nnx">Langkah berikutnya (tanggal)</label><input id="nnx" type="date" name="next_step_on"></div>
        </div>
        <button class="btn" style="margin-top:12px">Simpan lamaran</button>
      </form>
    </div>
  </div>

  <div class="stack">
    <div class="card">
      <h2>Checklist kesiapan</h2>
      @foreach ($cats as $key => $label)
        @if (isset($c['items'][$key]) && $c['items'][$key]->isNotEmpty())
          <div class="cl"><h3>{{ $label }}</h3>
            @foreach ($c['items'][$key] as $it)
              <div class="task">
                <form method="POST" action="{{ route('career.item.toggle', $it) }}">
                  @csrf @method('PATCH')
                  <input type="hidden" name="is_done" value="0">
                  <input type="checkbox" name="is_done" value="1" @checked($it->is_done) aria-label="{{ $it->title }}" onchange="this.form.requestSubmit()">
                </form>
                <div class="n" @if ($it->is_done) style="opacity:.55" @endif>{{ $it->title }}</div>
                <form method="POST" action="{{ route('career.item.destroy', $it) }}" onsubmit="return confirm('Hapus item ini?')">@csrf @method('DELETE')<button class="x" aria-label="Hapus {{ $it->title }}">✕</button></form>
              </div>
            @endforeach
          </div>
        @endif
      @endforeach
      <form method="POST" action="{{ route('career.item.store') }}" class="addrow" style="grid-template-columns:1fr 130px auto">
        @csrf
        <input name="title" placeholder="Item baru" aria-label="Item checklist baru" maxlength="160" required>
        <select name="category" aria-label="Kategori">@foreach ($cats as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach</select>
        <button class="btn">Tambah</button>
      </form>
    </div>

    <div class="card pc c-k kraft deco d2">
      <h2>Pelajaran dari penolakan</h2>
      @if ($c['missingLessons'])
        <p class="over" style="margin-top:8px">{{ $c['missingLessons'] }} penolakan belum ada pelajarannya. Isi dulu sebelum mengirim lamaran berikutnya.</p>
      @endif
      @forelse ($c['lessons'] as $l)
        <div class="lesson"><b>{{ $l->company }}</b><p style="white-space:pre-line">{{ $l->lesson }}</p></div>
      @empty
        @if (! $c['missingLessons'])<p class="mute" style="margin-top:8px">Belum ada. Penolakan itu data. Catat polanya, dan lamaran berikutnya makin tajam.</p>@endif
      @endforelse
    </div>
  </div>
</div>
@endsection