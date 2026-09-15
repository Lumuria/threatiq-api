<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttackTranslation extends Model
{
    protected $fillable = [
        'attack_id',
        'language',
        'title',
        'date',
        'type',
        'target',
        'damage',
        'severity',
        'description',
        'prevention',
        'detection',
        'solution',
    ];

    public function attack(): BelongsTo
    {
        return $this->belongsTo(Attack::class);
    }
}