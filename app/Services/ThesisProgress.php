<?php

namespace App\Services;

use App\Models\ThesisStage;

class ThesisProgress
{
    // Bobot progres per status. Ubah angka ini kalau mau lebih ketat atau longgar.
    public const WEIGHT = ['belum' => 0, 'berjalan' => 0.25, 'revisi' => 0.5, 'acc' => 1];

    public static function for(int $userId): array
    {
        $stages = ThesisStage::where('user_id', $userId)->orderBy('position')->get();
        $total  = $stages->count();
        $score  = $stages->sum(fn ($s) => self::WEIGHT[$s->status] ?? 0);
        $open   = $stages->where('status', '!=', 'acc');
        $today  = now()->startOfDay();

        return [
            'stages'       => $stages,
            'total'        => $total,
            'done'         => $stages->where('status', 'acc')->count(),
            'percent'      => $total ? (int) round($score / $total * 100) : 0,
            'current'      => $open->first(),
            'upcoming'     => $open->take(4),
            'overdue'      => $open->filter(fn ($s) => $s->target_date && $s->target_date->lt($today))->count(),
            'nextDeadline' => $open->filter(fn ($s) => $s->target_date)->sortBy('target_date')->first(),
        ];
    }
}