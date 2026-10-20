<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisStage extends Model
{
    protected $fillable = ['user_id', 'name', 'position', 'status', 'target_date', 'done_at', 'notes'];

    protected function casts(): array
    {
        return ['target_date' => 'date', 'done_at' => 'date'];
    }
}