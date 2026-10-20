<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerItem extends Model
{
    protected $fillable = ['user_id', 'category', 'title', 'is_done'];

    protected function casts(): array
    {
        return ['is_done' => 'boolean'];
    }
}
