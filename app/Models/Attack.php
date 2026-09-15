<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attack extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(AttackTranslation::class);
    }
}