<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class WasteScan extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'collection_point_id',
        'image_url',
        'ai_classification',
        'ai_advice',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
