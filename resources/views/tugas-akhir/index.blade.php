@extends('layouts.app')
@section('title', 'Tugas Akhir')
@section('content')
    @php $labels = ['belum' => 'Belum', 'berjalan' => 'Berjalan', 'revisi' => 'Revisi', 'acc' => 'ACC']; @endphp

    <div class="head">
        <div>
            <p class="mute">Progres keseluruhan</p>
            <h1>Tugas Akhir</h1>
        </div>
        <div style="text-align:right">
            <h2>{{ $percent }}%</h2>
            <p class="mute">{{ $done }} dari {{ $stages->count() }} tahap ACC</p>
        </div>
    </div>
    <div class="bar big"><i style="width:{{ $percent }}%"></i></div>

    @if (session('ok'))
    <p class="flash" role="status">{{ session('ok') }}</p>@endif
    @if ($errors->any())
    <p class="err" role="alert">{{ $errors->first() }}</p>@endif

    <div class="card pc c-y" style="margin-bottom:16px">
        @if ($current)
            <p class="mute">Posisimu sekarang</p>
            <h2>{{ $current->name }}</h2>
            @if ($current->target_date)
                <p class="{{ $current->target_date->isPast() ? 'over' : 'mute' }}">
                    Target:
                    {{ $current->target_date->locale('id')->translatedFormat('j F Y') }}{{ $current->target_date->isPast() ? ' (lewat)' : '' }}
                </p>
            @else
                <p class="mute">Belum ada target tanggal. Pasang satu di bawah.</p>
            @endif
        @else
            <h2>Semua tahap ACC. Selamat.</h2>
        @endif
    </div>

    <div class="two" style="margin-bottom:16px">
        <form method="POST" action="{{ route('thesis.info') }}" class="card">
            @csrf @method('PUT')
            <h2>Judul dan pembimbing</h2>
            <label for="title">Judul</label>
            <input id="title" name="title" value="{{ old('title', $thesis->title) }}" maxlength="255"
                placeholder="Belum ada judul">
            <label for="advisor">Dosen pembimbing</label>
            <input id="advisor" name="advisor" value="{{ old('advisor', $thesis->advisor) }}" maxlength="120">
            <button class="btn" style="margin-top:14px">Simpan</button>
        </form>

        <div class="card">
            <h2>Tahapan</h2>
            @foreach ($stages as $s)
                <div class="stage">
                    <span class="dot st-{{ $s->status }}"
                        aria-hidden="true">{{ $s->status === 'acc' ? '✓' : $loop->iteration }}</span>
                    <div><b>{{ $s->name }}</b>@if ($s->done_at)<span class="mute"> · ACC
                    {{ $s->done_at->format('d/m/Y') }}</span>@endif</div>
                    <form method="POST" action="{{ route('thesis.stage.destroy', $s) }}"
                        onsubmit="return confirm('Hapus tahap ini?')">
                        @csrf @method('DELETE')<button class="x" aria-label="Hapus {{ $s->name }}">✕</button>
                    </form>
                    <form method="POST" action="{{ route('thesis.stage.update', $s) }}" class="ctl">
                        @csrf @method('PATCH')
                        <select name="status" aria-label="Status {{ $s->name }}" onchange="this.form.requestSubmit()">
                            @foreach ($labels as $v => $l)
                            <option value="{{ $v }}" @selected($s->status === $v)>{{ $l }}</option>@endforeach
                        </select>
                        <input type="date" name="target_date" value="{{ optional($s->target_date)->format('Y-m-d') }}"
                            aria-label="Target {{ $s->name }}" onchange="this.form.requestSubmit()">
                    </form>
                </div>
            @endforeach
            <form method="POST" action="{{ route('thesis.stage.store') }}" style="display:flex;gap:8px;margin-top:14px">
                @csrf
                <input name="name" placeholder="Tambah tahap baru" aria-label="Nama tahap baru" required maxlength="120">
                <button class="btn">Tambah</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h2>Log bimbingan</h2>
        <form method="POST" action="{{ route('thesis.log.store') }}" class="two" style="margin-top:8px">
            @csrf
            <div><label for="held_on">Tanggal</label><input id="held_on" type="date" name="held_on"
                    value="{{ now()->toDateString() }}" required>
                <label for="summary">Arahan dosen</label><textarea id="summary" name="summary" required
                    placeholder="Apa yang dikatakan dosen?"></textarea>
            </div>
            <div><label for="follow_up">Tindak lanjut</label><textarea id="follow_up" name="follow_up"
                    style="min-height:174px"
                    placeholder="Apa yang harus kamu kerjakan sebelum bimbingan berikutnya?"></textarea></div>
            <div><button class="btn">Simpan catatan</button></div>
        </form>

        <div style="margin-top:12px">
            @forelse ($logs as $l)
                <div class="log {{ $l->is_done ? 'done' : '' }}">
                    <div style="display:flex;justify-content:space-between;gap:12px">
                        <b>{{ $l->held_on->locale('id')->translatedFormat('j F Y') }}</b>
                        <form method="POST" action="{{ route('thesis.log.destroy', $l) }}"
                            onsubmit="return confirm('Hapus catatan ini?')">@csrf @method('DELETE')<button class="x"
                                aria-label="Hapus catatan">✕</button></form>
                    </div>
                    <p class="t" style="white-space:pre-line">{{ $l->summary }}</p>
                    @if ($l->follow_up)
                        <form method="POST" action="{{ route('thesis.log.toggle', $l) }}" style="margin-top:6px">
                            @csrf @method('PATCH')
                            <label style="display:flex;gap:8px;align-items:flex-start;margin:0;color:inherit">
                                <input type="checkbox" name="is_done" value="1" @checked($l->is_done)
                                    onchange="this.form.requestSubmit()" style="width:22px;min-height:0;margin-top:2px">
                                <span class="t" style="white-space:pre-line">{{ $l->follow_up }}</span>
                            </label>
                        </form>
                    @endif
                </div>
            @empty
                <p class="mute">Belum ada catatan bimbingan. Catat setiap bimbingan, jangan mengandalkan ingatan.</p>
            @endforelse
        </div>
    </div>
@endsection