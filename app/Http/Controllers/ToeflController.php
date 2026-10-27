<?php

namespace App\Http\Controllers;

use App\Models\ToeflNote;
use App\Models\ToeflSetting;
use App\Models\ToeflTask;
use App\Models\ToeflTryout;
use App\Services\ToeflProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToeflController extends Controller
{
    public function index(Request $request)
    {
    $q = trim((string) $request->query('q')) ?: null;

    return view('toefl.index', ['t' => ToeflProgress::for($request->user()->id, $q)]);
}
    

    public function saveSetting(Request $request)
    {
        $data = $request->validate([
            'target_score'  => ['nullable', 'integer', 'between:310,677'],
            'test_date'     => ['nullable', 'date'],
            'weekly_target' => ['required', 'integer', 'between:1,14'],
        ]);
        ToeflSetting::updateOrCreate(['user_id' => $request->user()->id], $data);

        return back()->with('ok', 'Pengaturan tersimpan.');
    }

    public function storeTryout(Request $request)
    {
        $data = $request->validate([
            'taken_on'  => ['required', 'date', 'before_or_equal:today'],
            'listening' => ['required', 'integer', 'between:31,68'],
            'structure' => ['required', 'integer', 'between:31,68'],
            'reading'   => ['required', 'integer', 'between:31,67'],
            'notes'     => ['nullable', 'string', 'max:2000'],
        ]);
        $uid  = $request->user()->id;
        $prev = ToeflTryout::where('user_id', $uid)->orderByDesc('taken_on')->orderByDesc('id')->first();
        $new  = ToeflTryout::create($data + ['user_id' => $uid]);

        if (! $prev) {
            $msg = 'Titik awal tercatat. Sekarang kamu punya data, bukan tebakan.';
        } else {
            $a = ToeflProgress::total($prev);
            $b = ToeflProgress::total($new);
            $msg = $b > $a ? "Skor naik! $a → $b. Pertahankan."
                 : ($b < $a ? "Skor turun ($a → $b). Bongkar kesalahannya, jangan dibiarkan."
                            : 'Skor sama. Cari section yang masih bisa didorong.');
        }

        return back()->with('ok', $msg);
    }

    public function destroyTryout(ToeflTryout $tryout)
    {
        $this->own($tryout)->delete();

        return back()->with('ok', 'Tryout dihapus.');
    }

    public function storeTask(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:160'],
            'section' => ['required', 'in:listening,structure,reading,vocab,umum'],
            'due_on'  => ['nullable', 'date'],
        ]);
        ToeflTask::create($data + ['user_id' => $request->user()->id]);

        return back()->with('ok', 'Target ditambahkan.');
    }

    public function toggleTask(Request $request, ToeflTask $task)
    {
        $done = $request->boolean('is_done');
        $this->own($task)->update(['done_at' => $done ? now()->toDateString() : null]);

        return back()->with('ok', $done ? 'Satu target beres. Lanjut.' : 'Target dibuka kembali.');
    }

    public function destroyTask(ToeflTask $task)
    {
        $this->own($task)->delete();

        return back()->with('ok', 'Target dihapus.');
    }

    public function saveNote(Request $request)
    {
        $request->validate(['body' => ['nullable', 'string', 'max:5000']]);
        ToeflNote::updateOrCreate(
            ['user_id' => $request->user()->id, 'noted_on' => now()->toDateString()],
            ['body' => $request->body]
        );

        return back()->with('ok', 'Catatan tersimpan.');
    }

    private function own($model)
    {
        abort_unless((int) $model->user_id === (int) Auth::id(), 403);

        return $model;
    }
}