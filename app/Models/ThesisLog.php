<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThesisLog extends Model
{
    protected $fillable = ['user_id', 'held_on', 'summary', 'follow_up', 'is_done'];

    protected function casts(): array
    {
        return ['held_on' => 'date', 'is_done' => 'boolean'];
    }
}
