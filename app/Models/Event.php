<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    const STATUS_DRAFT  = 'draft';
    const STATUS_OPEN   = 'open';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'term_id',
        'title',
        'description',
        'event_date',
        'location',
        'status',
        'cert_template',
        'fb_post_url',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
        ];
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_participants', 'event_id', 'user_id')
                    ->withPivot(['status', 'proof_type', 'proof_path', 'remarks', 'submitted_at', 'joined_at'])
                    ->using(EventParticipant::class);
    }

    public function eventParticipants(): HasMany
    {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }

    public function isDraft(): bool  { return $this->status === self::STATUS_DRAFT; }
    public function isOpen(): bool   { return $this->status === self::STATUS_OPEN; }
    public function isClosed(): bool { return $this->status === self::STATUS_CLOSED; }

    public function hasParticipant(User $user): bool
    {
        return $this->participants()->where('user_id', $user->id)->exists();
    }

    public function participantRecord(User $user): ?EventParticipant
    {
        return $this->eventParticipants()->where('user_id', $user->id)->first();
    }

    public function scopeVisible($query)
    {
        return $query->where('status', '!=', self::STATUS_DRAFT);
    }
}
