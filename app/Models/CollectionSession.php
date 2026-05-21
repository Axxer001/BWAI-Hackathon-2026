<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CollectionSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'barangay_id',
        'schedule_id',
        'collector_id',
        'truck_id',
        'session_date',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'session_date' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    // Status helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isActive(): bool
    {
        return $this->status === 'ongoing';
    }
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(CollectionSchedule::class, 'schedule_id');
    }

    public function sessionPoints(): HasMany
    {
        return $this->hasMany(SessionPoint::class, 'session_id')->orderBy('route_order');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }


    public function nextPendingPoint(): ?SessionPoint
    {
        return $this->sessionPoints()
            ->where('status', 'pending')
            ->orderBy('route_order')
            ->first();
    }

    public function report(): HasOne
    {
        return $this->hasOne(CollectionReport::class, 'session_id');
    }
}