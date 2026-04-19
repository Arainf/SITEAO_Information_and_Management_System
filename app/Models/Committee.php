<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Committee extends Model
{
    protected $fillable = ['name', 'description'];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function userPositions(): HasMany
    {
        return $this->hasMany(UserPosition::class, 'position_id')
            ->whereHas('position', fn($q) => $q->where('committee_id', $this->id));
    }
}
