<?php

namespace App\Http\Controllers;

use App\Models\CareerItem;
use App\Models\JobApplication;
use App\Services\CareerProgress;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    private const DEFAULTS = [
        ['cv', 'CV 1 halaman, ATS-friendly, versi Inggris'],
        ['cv', 'Minta 1 orang mereview CV-mu'],
        ['portofolio', 'Portofolio online dengan 3 proyek yang bisa dibuka'],
        ['portofolio', 'Progres Hub: README, screenshot, dan demo (data palsu)'],
        ['portofolio', 'GitHub rapi: README profil dan repo unggulan di-pin'],
        ['skill', 'Latihan 10 soal wawancara teknis'],
        ['skill', 'Satu skill utama yang bisa dibuktikan lewat proyek'],
        ['jaringan', 'LinkedIn lengkap: foto, ringkasan, proyek'],
        ['jaringan', 'Daftar 20 perusahaan target'],
        ['jaringan', 'Hubungi 3 orang di industri bulan ini'],
    ];

    private const STATUSES = 'wishlist,applied,interview,offer,rejected,withdrawn';

    public function index(Request $request)
    {
        $uid = $request->user()->id;

        if (! CareerItem::where('user_id', $uid)->exists()) {
            foreach (self::DEFAULTS as [$cat, $title]) {
                CareerItem::create(['user_id' => $uid, 'category' => $cat, 'title' => $title]);
            }
        }

        return view('career.index', ['c' => CareerProgress::for($uid)]);
    }

    public function storeApp(Request $request)
    {
        $data = $this->validated($request);
        $data = $this->autoDate($data);
        JobApplication::create($data + ['user_id' => $request->user()->id]);

        return back()->with('ok', 'Lamaran dicatat.');
    }

    public function updateApp(Request $request, JobApplication $application)
    {
        $this->own($application);
        $data    = $this->autoDate($this->validated($request));
        $changed = $application->status !== $data['status'];
        $application->update($data);

        $msg = 'Lamaran diperbarui.';
        if ($changed) {
            $msg = match ($data['status']) {
                'offer'     => 'OFFER! Kamu layak mendapatkannya. Pikirkan negosiasinya dengan kepala dingin.',
                'rejected'  => 'Tercatat. Sekarang tulis pelajarannya. Penolakan tanpa pelajaran hanya jadi luka.',
                'interview' => 'Masuk tahap interview. Siapkan jawaban teknis dan cerita tentang dirimu.',
                default     => $msg,
            };
        }

        return back()->with('ok', $msg);
    }

    public function destroyApp(JobApplication $application)
    {
        $this->own($application)->delete();

        return back()->with('ok', 'Lamaran dihapus.');
    }

    public function storeItem(Request $request)
    {
        $data = $request->validate([
            'category' => ['required', 'in:cv,portofolio,skill,jaringan'],
            'title'    => ['required', 'string', 'max:160'],
        ]);
        CareerItem::create($data + ['user_id' => $request->user()->id]);

        return back()->with('ok', 'Item checklist ditambahkan.');
    }

    public function toggleItem(Request $request, CareerItem $item)
    {
        $done = $request->boolean('is_done');
        $this->own($item)->update(['is_done' => $done]);

        return back()->with('ok', $done ? 'Satu item siap. Lanjut.' : 'Item dibuka kembali.');
    }

    public function destroyItem(CareerItem $item)
    {
        $this->own($item)->delete();

        return back()->with('ok', 'Item dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'company'      => ['required', 'string', 'max:120'],
            'position'     => ['required', 'string', 'max:120'],
            'status'       => ['required', 'in:' . self::STATUSES],
            'url'          => ['nullable', 'url:http,https', 'max:255'],
            'applied_on'   => ['nullable', 'date'],
            'next_step_on' => ['nullable', 'date'],
            'notes'        => ['nullable', 'string', 'max:3000'],
            'lesson'       => ['nullable', 'string', 'max:3000'],
        ]);
    }

    private function autoDate(array $data): array
    {
        if (empty($data['applied_on']) && in_array($data['status'], ['applied', 'interview', 'offer', 'rejected'], true)) {
            $data['applied_on'] = now()->toDateString();
        }

        return $data;
    }

    private function own($model)
    {
        abort_unless((int) $model->user_id === (int) auth()->id(), 403);

        return $model;
    }
}