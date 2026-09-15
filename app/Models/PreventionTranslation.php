<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreventionTranslation extends Model
{
    protected $fillable = [
        'prevention_id',
        'language',
        'title',
        'description',
        'tips',
    ];

    protected $casts = [
        'tips' => 'array',
    ];

    public function prevention(): BelongsTo
    {
        return $this->belongsTo(Prevention::class);
    }
}