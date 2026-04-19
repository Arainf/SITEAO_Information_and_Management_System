<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventParticipant extends Pivot
{
    protected $table = 'event_participants';

    public $incrementing = false;
    public $timestamps   = false;


    const STATUS_PENDING_PROOF = 'pending_proof';
    const STATUS_SUBMITTED     = 'submitted';
    const STATUS_APPROVED      = 'approved';
    const STATUS_REJECTED      = 'rejected';

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'proof_type',
        'proof_path',
        'remarks',
        'submitted_at',
        'cert_released_at',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at'     => 'datetime',
            'cert_released_at' => 'datetime',
            'joined_at'        => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hasCert(): bool        { return $this->cert_released_at !== null; }
    public function isPendingProof(): bool { return $this->status === self::STATUS_PENDING_PROOF; }
    public function isSubmitted(): bool    { return $this->status === self::STATUS_SUBMITTED; }
    public function isApproved(): bool     { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool     { return $this->status === self::STATUS_REJECTED; }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_PROOF => 'badge-pending',
            self::STATUS_SUBMITTED     => 'badge-moderator',
            self::STATUS_APPROVED      => 'badge-active',
            self::STATUS_REJECTED      => 'badge-inactive',
            default                    => 'badge-pending',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_PROOF => 'Pending Proof',
            self::STATUS_SUBMITTED     => 'Submitted',
            self::STATUS_APPROVED      => 'Approved',
            self::STATUS_REJECTED      => 'Rejected',
            default                    => 'Unknown',
        };
    }
}
