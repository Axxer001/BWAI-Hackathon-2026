<?php

namespace App\Http\Controllers;

use App\Models\Resource;

class ResourceController extends BaseApiController
{
    
    protected string $model = Resource::class;

    
    protected array $rules = [
        'name' => 'required|string|max:255',
        'user_id' => 'required|exists:users,id',
    ];
}
