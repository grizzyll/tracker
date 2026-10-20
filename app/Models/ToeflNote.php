<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToeflNote extends Model
{
    protected $fillable = ['user_id', 'noted_on', 'body'];

    protected function casts(): array
    {
        return ['noted_on' => 'date'];
    }
}
