<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionPoint extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'session_id',
        'garbage_point_id',
        'route_order',
        'status',
        'notified_at',
        'collected_at',
    ];

    protected $casts = [
        'notified_at'   => 'datetime',
        'collected_at'  => 'datetime',
        'route_order'   => 'integer',
    ];

    // Status helpers
    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isNotified(): bool  { return $this->status === 'notified'; }
    public function isCollected(): bool { return $this->status === 'collected'; }
    public function isSkipped(): bool   { return $this->status === 'skipped'; }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CollectionSession::class, 'session_id');
    }

    public function garbagePoint(): BelongsTo
    {
        return $this->belongsTo(GarbagePoint::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get all users assigned to this point and send notifications.
     */
    public function assignedUsers()
    {
        return $this->garbagePoint
                    ->assignedUsers()
                    ->where('is_active', true);
    }
}