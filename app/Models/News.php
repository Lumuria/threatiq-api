<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'summary',
        'content',
        'category',
        'severity',
        'source',
        'date',
        'recommendations',
    ];

    protected $casts = [
        'title' => 'array',
        'summary' => 'array',
        'content' => 'array',
        'category' => 'array',
        'severity' => 'array',
        'source' => 'array',
        'recommendations' => 'array',
        'date' => 'date:Y-m-d',
    ];
}