<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionReport extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'session_id',
        'total_points',
        'completed_points',
        'total_notified_users',
        'notes',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function completionRate(): float
    {
        if ($this->total_points === 0) return 0.0;
        return round(($this->completed_points / $this->total_points) * 100, 2);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CollectionSession::class, 'session_id');
    }
}