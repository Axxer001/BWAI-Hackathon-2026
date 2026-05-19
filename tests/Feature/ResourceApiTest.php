<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceApiTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_can_list_resources(): void
    {
        $user = User::factory()->create();
        Resource::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/resources');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'user_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    
    public function test_can_filter_resources_by_user_id(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Resource::factory()->create(['user_id' => $user1->id]);
        Resource::factory()->create(['user_id' => $user2->id]);

        $response = $this->getJson('/api/resources?user_id=' . $user1->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    
    public function test_can_create_resource(): void
    {
        $user = User::factory()->create();
        
        $payload = [
            'name' => 'Laser Beams',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/resources', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'name' => 'Laser Beams',
                    'user_id' => $user->id,
                ]
            ]);

        $this->assertDatabaseHas('resources', [
            'name' => 'Laser Beams',
            'user_id' => $user->id,
        ]);
    }

    
    public function test_create_resource_requires_validation(): void
    {
        $response = $this->postJson('/api/resources', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'user_id']);
    }

    
    public function test_can_show_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/resources/{$resource->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $resource->name);
    }

    
    public function test_show_non_existent_resource_returns_404(): void
    {
        $response = $this->getJson('/api/resources/999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Resource not found.'
            ]);
    }

    
    public function test_can_update_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/resources/{$resource->id}", [
            'name' => 'Grappling Hook',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Grappling Hook');
    }

    
    public function test_can_delete_resource(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/resources/{$resource->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('resources', ['id' => $resource->id]);
    }
}
