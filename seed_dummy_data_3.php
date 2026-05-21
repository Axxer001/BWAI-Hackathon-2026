<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sessions = \App\Models\CollectionSession::all();

if($sessions->count() > 0) {
    $count = 0;
    foreach ($sessions as $session) {
        // Only create if it doesn't have a report yet
        if (!\App\Models\CollectionReport::where('session_id', $session->id)->exists()) {
            \App\Models\CollectionReport::create([
                'session_id' => $session->id,
                'total_points' => rand(20, 50),
                'completed_points' => rand(15, 30), // This adds to the tons (each point is 0.1 tons)
                'total_notified_users' => rand(50, 200),
                'generated_at' => now(),
            ]);
            $count++;
        }
    }
    echo "Added $count CollectionReports.\n";
} else {
    echo "No CollectionSessions found to attach CollectionReports.\n";
}
