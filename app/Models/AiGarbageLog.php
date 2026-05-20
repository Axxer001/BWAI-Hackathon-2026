<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGarbageLog extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'garbage_point_id',
        'image_url',
        'ai_advice',
        'garbage_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function garbagePoint(): BelongsTo
    {
        return $this->belongsTo(GarbagePoint::class);
    }
}