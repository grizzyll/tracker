<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = ['user_id', 'company', 'position', 'status', 'url', 'applied_on', 'next_step_on', 'notes', 'lesson'];

    protected function casts(): array
    {
        return ['applied_on' => 'date', 'next_step_on' => 'date'];
    }
}
