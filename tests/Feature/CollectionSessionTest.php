<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barangay;
use App\Models\GarbagePoint;
use App\Models\CollectionSession;
use App\Models\SessionPoint;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectionSessionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $barangay;

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
            'full_name' => 'James Benedict Rojas',
            'email' => 'rojas@example.com',
            'password' => bcrypt('password'),
            'role' => 'collector',
            'barangay_id' => $this->barangay->id,
        ]);
    }

    public function test_route_map_auto_creates_session_if_none_exists()
    {
        $this->actingAs($this->user);

        // Verify no collection session initially
        $this->assertEquals(0, CollectionSession::count());

        $response = $this->get('/dashboard/route-map');

        $response->assertStatus(200);
        $this->assertEquals(1, CollectionSession::count());
        $this->assertEquals(4, GarbagePoint::count()); // Autoseeded 4 points
    }

    public function test_collector_can_start_route()
    {
        $this->actingAs($this->user);

        // Access page to trigger auto-creation
        $this->get('/dashboard/route-map');
        $session = CollectionSession::first();

        $this->assertEquals('pending', $session->status);

        $response = $this->post(route('dashboard.start-route', $session->id));

        $response->assertRedirect();
        $this->assertEquals('active', $session->fresh()->status);
        $this->assertNotNull($session->fresh()->started_at);
    }

    public function test_collector_can_update_point_status()
    {
        $this->actingAs($this->user);

        $this->get('/dashboard/route-map');
        $session = CollectionSession::first();
        
        // Start the route session
        $this->post(route('dashboard.start-route', $session->id));

        $sessionPoint = SessionPoint::where('session_id', $session->id)->first();
        $this->assertEquals('pending', $sessionPoint->status);

        $response = $this->post(route('dashboard.update-point-status', [$session->id, $sessionPoint->id]), [
            'status' => 'collected'
        ]);

        $response->assertRedirect();
        $this->assertEquals('collected', $sessionPoint->fresh()->status);
        $this->assertNotNull($sessionPoint->fresh()->collected_at);
    }

    public function test_collector_can_view_point_logs()
    {
        $this->actingAs($this->user);

        // Access route map to auto-seed and create a session
        $this->get('/dashboard/route-map');
        $session = CollectionSession::first();
        $sessionPoint = SessionPoint::first();

        // Mark a point as collected
        $this->post(route('dashboard.update-point-status', [$session->id, $sessionPoint->id]), [
            'status' => 'collected'
        ]);

        $response = $this->get('/dashboard/point-logs');

        $response->assertStatus(200);
        $response->assertSee('Collection History Log');
        $response->assertSee('Collected');
    }

    public function test_collector_can_log_truck_full()
    {
        $this->actingAs($this->user);

        // Start session
        $this->get('/dashboard/route-map');
        $session = CollectionSession::first();
        $this->post(route('dashboard.start-route', $session->id));

        // Display page
        $response = $this->get('/dashboard/truck-full');
        $response->assertStatus(200);

        $collectionPoint = \App\Models\CollectionPoint::first();

        $postResponse = $this->post(route('dashboard.log-truck-full'), [
            'session_id' => $session->id,
            'collection_point_id' => $collectionPoint->id,
            'estimated_load' => '100%',
        ]);

        $postResponse->assertRedirect();
        $this->assertEquals(1, \App\Models\TruckFullEvent::count());
    }
}
