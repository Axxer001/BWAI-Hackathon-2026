<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
$cp = \App\Models\CollectionPoint::first();

if($user) {
    for ($i=0; $i<15; $i++) {
        \App\Models\WasteScan::create([
            'user_id' => $user->id,
            'collection_point_id' => $cp ? $cp->id : null,
            'image_url' => 'dummy_image.jpg',
            'ai_classification' => 'Plastic',
            'ai_advice' => 'Please recycle.'
        ]);
    }
    echo "Added 15 WasteScans.\n";
}

$session = \App\Models\CollectionSession::first();
$gp = \App\Models\GarbagePoint::first();

if($session && $gp) {
    for ($i=0; $i<42; $i++) {
        \App\Models\SessionPoint::create([
            'session_id' => $session->id,
            'garbage_point_id' => $gp->id,
            'route_order' => $i + 1,
            'status' => 'collected',
            'collected_at' => now(),
        ]);
    }
    echo "Added 42 collected SessionPoints.\n";
} else {
    echo "No CollectionSession or GarbagePoint found to attach SessionPoints.\n";
}
