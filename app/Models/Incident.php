<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'type_ar',
        'type_en',
        'severity_ar',
        'severity_en',
        'status_ar',
        'status_en',
        'description_ar',
        'description_en',
    ];
}