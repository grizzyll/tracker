<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToeflSetting extends Model
{
    protected $fillable = ['user_id', 'target_score', 'test_date', 'weekly_target'];

    protected function casts(): array
    {
        return ['test_date' => 'date'];
    }
}
