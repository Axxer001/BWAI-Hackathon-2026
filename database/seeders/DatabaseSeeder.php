<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        
        Resource::factory(5)->create([
            'user_id' => $user->id,
        ]);

        
        User::factory(5)->create()->each(function ($u) {
            Resource::factory(3)->create([
                'user_id' => $u->id,
            ]);
        });
    }
}
