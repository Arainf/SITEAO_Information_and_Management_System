<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    protected $fillable = ['committee_id', 'name', 'description'];

    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class);
    }

    public function userPositions(): HasMany
    {
        return $this->hasMany(UserPosition::class);
    }
}
