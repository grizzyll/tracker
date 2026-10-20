<?php

namespace App\Services;

use App\Models\CareerItem;
use App\Models\JobApplication;

class CareerProgress
{
    public static function for(int $uid): array
    {
        $apps  = JobApplication::where('user_id', $uid)->orderByDesc('updated_at')->get();
        $items = CareerItem::where('user_id', $uid)->orderBy('id')->get();
        $by    = $apps->countBy('status');
        $n     = fn (string $s) => (int) $by->get($s, 0);
        $today = now()->startOfDay();

        $itemsDone = $items->where('is_done', true)->count();
        $rejected  = $apps->where('status', 'rejected');

        return [
            'apps'           => $apps,
            'items'          => $items->groupBy('category'),
            'itemsTotal'     => $items->count(),
            'itemsDone'      => $itemsDone,
            'percent'        => $items->count() ? (int) round($itemsDone / $items->count() * 100) : 0,
            'total'          => $apps->count(),
            'sent'           => $n('applied') + $n('interview') + $n('offer') + $n('rejected'),
            'active'         => $n('applied') + $n('interview'),
            'interview'      => $n('interview'),
            'offer'          => $n('offer'),
            'rejected'       => $n('rejected'),
            'upcoming'       => $apps->filter(fn ($a) => $a->next_step_on
                                    && $a->next_step_on->gte($today)
                                    && in_array($a->status, ['applied', 'interview']))
                                ->sortBy('next_step_on')->values(),
            'lessons'        => $rejected->filter(fn ($a) => filled($a->lesson))->values(),
            'missingLessons' => $rejected->filter(fn ($a) => blank($a->lesson))->count(),
        ];
    }
}