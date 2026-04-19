<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TermOfficer extends Model
{
    protected $fillable = ['term_id', 'user_id', 'position'];

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
