<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barangay;
use App\Models\CollectionPoint;
use App\Models\WasteScan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WasteAssessmentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $barangay;
    protected $collectionPoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barangay = Barangay::create([
            'id' => '6b70fc9b-5439-11f1-a7a3-74d4dd68a178',
            'name' => 'Calarian',
            'district' => 'West',
        ]);

        $this->user = User::create([
            'id' => '16267956-822f-4f4e-9be5-29653d33748a',
            'full_name' => 'Rham Rojas',
            'email' => 'rham@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'barangay_id' => $this->barangay->id,
        ]);

        $uuid = 'efbdab2d-221a-426a-aea2-7ce1a265c34e';
        \Illuminate\Support\Facades\DB::table('collection_points')->insert([
            'id' => $uuid,
            'barangay_id' => $this->barangay->id,
            'name' => 'Calarian Central Hub',
            'latitude' => 6.9200,
            'longitude' => 122.0300,
            'address' => 'Plaza',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\DB::table('garbage_points')->insert([
            'id' => $uuid,
            'barangay_id' => $this->barangay->id,
            'name' => 'Calarian Central Hub',
            'latitude' => 6.9200,
            'longitude' => 122.0300,
            'address' => 'Plaza',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->collectionPoint = CollectionPoint::find($uuid);
    }

    public function test_resident_can_view_ai_scanner_dashboard()
    {
        $this->actingAs($this->user);

        // Pre-create a scan
        WasteScan::create([
            'user_id' => $this->user->id,
            'collection_point_id' => $this->collectionPoint->id,
            'image_url' => '/storage/garbage_logs/test.jpg',
            'ai_advice' => 'Wash and rinse bottle.',
            'ai_classification' => 'Plastic Bottle'
        ]);

        $response = $this->get('/dashboard/ai-scanner');

        $response->assertStatus(200);
        $response->assertSee('AI Waste Scanner');
        $response->assertSee('Calarian Central Hub');
        $response->assertSee('Plastic Bottle');
    }

    public function test_assess_endpoint_analyzes_image_successfully()
    {
        $this->withoutExceptionHandling();
        Storage::fake('public');
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'name' => 'Plastic Bottle',
                                        'category' => 'Non-Biodegradable',
                                        'preparation_advice' => 'Rinse and dry before disposal.'
                                    ])
                                ]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $this->actingAs($this->user);

        $file = UploadedFile::fake()->create('waste.jpg', 100, 'image/jpeg');

        $response = $this->postJson('/api/assess-waste', [
            'image' => $file,
            'user_id' => $this->user->id,
            'collection_point_id' => $this->collectionPoint->id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.name', 'Plastic Bottle');
        $response->assertJsonPath('data.category', 'Non-Biodegradable');

        $this->assertDatabaseHas('waste_scans', [
            'user_id' => $this->user->id,
            'ai_classification' => 'Plastic Bottle'
        ]);
    }
}
