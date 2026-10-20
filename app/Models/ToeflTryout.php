<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToeflTryout extends Model
{
    protected $fillable = ['user_id', 'taken_on', 'listening', 'structure', 'reading', 'notes'];

    protected function casts(): array
    {
        return ['taken_on' => 'date'];
    }
}
