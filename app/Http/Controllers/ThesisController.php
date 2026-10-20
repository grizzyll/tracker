<?php

namespace App\Http\Controllers;

use App\Models\Thesis;
use App\Models\ThesisLog;
use App\Models\ThesisStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ThesisProgress;

class ThesisController extends Controller
{
    private const DEFAULT_STAGES = [
        'Pengajuan judul',
        'ACC judul',
        'Bab 1: Pendahuluan',
        'Bab 2: Tinjauan pustaka',
        'Bab 3: Metodologi',
        'Seminar proposal',
        'Bab 4: Hasil dan pembahasan',
        'Bab 5: Penutup',
        'Sidang akhir',
        'Revisi pasca sidang dan pengumpulan',
    ];

    public function index(Request $request)
{
    $uid = $request->user()->id;

    if (! ThesisStage::where('user_id', $uid)->exists()) {
        foreach (self::DEFAULT_STAGES as $i => $name) {
            ThesisStage::create(['user_id' => $uid, 'name' => $name, 'position' => $i + 1]);
        }
    }

    $thesis = Thesis::firstOrCreate(['user_id' => $uid]);
    $logs   = ThesisLog::where('user_id', $uid)->orderByDesc('held_on')->orderByDesc('id')->get();

    return view('tugas-akhir.index', ThesisProgress::for($uid) + compact('thesis', 'logs'));
}

    public function saveInfo(Request $request)
    {
        $data = $request->validate([
            'title'   => ['nullable', 'string', 'max:255'],
            'advisor' => ['nullable', 'string', 'max:120'],
        ]);
        Thesis::updateOrCreate(['user_id' => $request->user()->id], $data);

        return back()->with('ok', 'Info tersimpan.');
    }

    public function storeStage(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:120']]);
        $uid = $request->user()->id;
        $pos = (int) ThesisStage::where('user_id', $uid)->max('position');
        ThesisStage::create(['user_id' => $uid, 'name' => $request->name, 'position' => $pos + 1]);

        return back()->with('ok', 'Tahap ditambahkan.');
    }

    public function updateStage(Request $request, ThesisStage $stage)
    {
        $this->own($stage);
        $data = $request->validate([
            'status'      => ['required', 'in:belum,berjalan,revisi,acc'],
            'target_date' => ['nullable', 'date'],
        ]);
        $data['done_at'] = $data['status'] === 'acc' ? ($stage->done_at ?? now()) : null;
        $stage->update($data);

        return back()->with('ok', $data['status'] === 'acc'
            ? 'ACC! Satu tahap tuntas. Lanjut ke tahap berikutnya.'
            : 'Tahap diperbarui.');
    }

    public function destroyStage(ThesisStage $stage)
    {
        $this->own($stage)->delete();

        return back()->with('ok', 'Tahap dihapus.');
    }

    public function storeLog(Request $request)
    {
        $data = $request->validate([
            'held_on'   => ['required', 'date'],
            'summary'   => ['required', 'string', 'max:5000'],
            'follow_up' => ['nullable', 'string', 'max:5000'],
        ]);
        ThesisLog::create($data + ['user_id' => $request->user()->id]);

        return back()->with('ok', 'Catatan bimbingan tersimpan.');
    }

    public function toggleLog(Request $request, ThesisLog $log)
    {
        $this->own($log)->update(['is_done' => $request->boolean('is_done')]);

        return back();
    }

    public function destroyLog(ThesisLog $log)
    {
        $this->own($log)->delete();

        return back()->with('ok', 'Catatan dihapus.');
    }

    private function own($model)
    {
        abort_unless((int) $model->user_id === (int) Auth::id(), 403);

        return $model;
    }
}