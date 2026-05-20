<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'notifications';

    protected $fillable = [
        'session_point_id',
        'user_id',
        'channel',
        'status',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Status helpers
    public function isPending(): bool { return $this->status === 'pending'; }
    public function isSent(): bool    { return $this->status === 'sent'; }
    public function isFailed(): bool  { return $this->status === 'failed'; }

    public function sessionPoint(): BelongsTo
    {
        return $this->belongsTo(SessionPoint::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}