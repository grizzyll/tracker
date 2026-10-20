<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToeflTask extends Model
{
    protected $fillable = ['user_id', 'title', 'section', 'due_on', 'done_at'];

    protected function casts(): array
    {
        return ['due_on' => 'date', 'done_at' => 'date'];
    }
}
