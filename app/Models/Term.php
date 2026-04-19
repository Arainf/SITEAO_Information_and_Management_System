<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_active'  => 'boolean',
        ];
    }

    public function officers(): HasMany
    {
        return $this->hasMany(TermOfficer::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
