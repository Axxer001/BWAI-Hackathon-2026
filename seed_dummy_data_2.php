<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$session = \App\Models\CollectionSession::first();

if($session) {
    for ($i=0; $i<42; $i++) {
        $gp = \App\Models\GarbagePoint::create([
            'barangay_id' => $session->barangay_id,
            'name' => 'Dummy Point ' . $i,
            'address' => 'Dummy Address',
            'latitude' => 6.9,
            'longitude' => 122.0,
            'is_active' => true,
        ]);
        
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
    echo "No CollectionSession found.\n";
}
