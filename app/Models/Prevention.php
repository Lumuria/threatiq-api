<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prevention extends Model
{
    protected $fillable = [
        'category_ar',
        'category_en',
        'importance_ar',
        'importance_en',
        'difficulty_ar',
        'difficulty_en',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PreventionTranslation::class);
    }
}