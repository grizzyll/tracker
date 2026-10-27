<?php

namespace App\Services;

use App\Models\ToeflNote;
use App\Models\ToeflSetting;
use App\Models\ToeflTask;
use App\Models\ToeflTryout;

class ToeflProgress
{
    public const MIN_TOTAL = 310;
    public const SECTIONS = ['listening' => 'Listening', 'structure' => 'Structure', 'reading' => 'Reading'];

    public static function total(ToeflTryout $t): int
    {
        return (int) round(($t->listening + $t->structure + $t->reading) * 10 / 3);
    }

    public static function for(int $uid, ?string $q = null): array
    {
        $setting = ToeflSetting::firstOrCreate(['user_id' => $uid]);
        $tryouts = ToeflTryout::where('user_id', $uid)->orderBy('taken_on')->orderBy('id')->get();
        $latest = $tryouts->last();
        $target = $setting->target_score;
        $total = $latest ? self::total($latest) : null;

        $percent = ($total && $target && $target > self::MIN_TOTAL)
            ? max(0, min(100, (int) round(($total - self::MIN_TOTAL) / ($target - self::MIN_TOTAL) * 100)))
            : 0;

        $sections = [];
        foreach (self::SECTIONS as $key => $label) {
            $score = $latest ? $latest->{$key} : null;
            $sections[] = [
                'label' => $label,
                'score' => $score,
                'pct' => ($score && $target) ? min(100, (int) round($score / ($target / 10) * 100)) : 0,
            ];
        }

        $weekStart = now()->startOfWeek()->toDateString();
        $weekDone = ToeflTask::where('user_id', $uid)->where('done_at', '>=', $weekStart)->count();
        $weekTarget = (int) $setting->weekly_target;
        $daysLeft = $setting->test_date ? (int) now()->startOfDay()->diffInDays($setting->test_date, false) : null;

        $milestones = [
            [
                'label' => 'Tryout pertama',
                'done' => $tryouts->isNotEmpty(),
                'info' => $tryouts->isNotEmpty() ? $tryouts->first()->taken_on->format('d/m/Y') : 'Belum dimulai',
            ],
            [
                'label' => 'Capai skor target' . ($target ? " ($target)" : ''),
                'done' => (bool) ($target && $total && $total >= $target),
                'info' => $total ? "Skor $total" : 'Belum dimulai',
            ],
            [
                'label' => "Konsisten belajar {$weekTarget}x/minggu",
                'done' => $weekDone >= $weekTarget,
                'info' => "$weekDone/$weekTarget minggu ini",
            ],
        ];

        return [
            'setting' => $setting,
            'target' => $target,
            'total' => $total,
            'percent' => $percent,
            'sections' => $sections,
            'tryouts' => $tryouts->reverse()->values(),
            'tasks' => ToeflTask::where('user_id', $uid)->whereNull('done_at')
                ->orderByRaw('due_on is null')->orderBy('due_on')->orderBy('id')->get(),
            'doneTasks' => ToeflTask::where('user_id', $uid)->whereNotNull('done_at')
                ->orderByDesc('done_at')->limit(5)->get(),
            'weekDone' => $weekDone,
            'weekTarget' => $weekTarget,
            'weekPct' => $weekTarget ? min(100, (int) round($weekDone / $weekTarget * 100)) : 0,
            'daysLeft' => $daysLeft,
            'milestones' => $milestones,
            'note' => ToeflNote::where('user_id', $uid)->where('noted_on', now()->toDateString())->first(),
            'notes' => ToeflNote::where('user_id', $uid)
                ->whereNotNull('body')->where('body', '!=', '')
                ->when($q, fn($x) => $x->where('body', 'like', '%' . addcslashes($q, '%_\\') . '%'))
                ->orderByDesc('noted_on')->get(),
            'q' => $q,
        ];
    }
}