<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckFullEvent extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'session_id',
        'collection_point_id',
        'logged_at',
        'dumping_site',
        'resume_status',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(CollectionSession::class, 'session_id');
    }

    public function collectionPoint(): BelongsTo
    {
        return $this->belongsTo(CollectionPoint::class, 'collection_point_id');
    }
}
