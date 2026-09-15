<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Awareness extends Model
{
    protected $fillable = [
        'title',
        'duration',
        'modules',
    ];

    protected $casts = [
        'title' => 'array',
        'duration' => 'array',
        'modules' => 'array',
    ];
}