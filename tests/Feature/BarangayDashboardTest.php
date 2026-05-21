<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barangay;
use App\Models\Truck;
use App\Models\CollectionSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BarangayDashboardTest extends TestCase
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
            'id' => '16267956-822f-4f4e-9be5-29653d33748b',
            'full_name' => 'Barangay Captain Rojas',
            'email' => 'captain@example.com',
            'password' => bcrypt('password'),
            'role' => 'barangay',
            'barangay_id' => $this->barangay->id,
        ]);
    }

    /**
     * Test accessing the dashboard home as a barangay admin.
     */
    public function test_barangay_dashboard_home()
    {
        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.partials.barangay.dashboard');
        $response->assertSee('Barangay Admin Panel');
    }

    /**
     * Test scheduling views and routing actions.
     */
    public function test_barangay_schedules_view()
    {
        $response = $this->actingAs($this->user)->get('/dashboard/schedules');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.partials.barangay.schedules');
    }

    public function test_store_collection_schedule()
    {
        $point = \App\Models\CollectionPoint::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'barangay_id' => $this->barangay->id,
            'name' => 'Calarian Center',
            'latitude' => 6.92,
            'longitude' => 122.03,
            'address' => 'Plaza',
        ]);

        $response = $this->actingAs($this->user)->post('/dashboard/schedules', [
            'name' => 'Morning Commercial Route',
            'days_of_week' => ['monday'],
            'collection_time' => '08:00',
            'frequency' => 'weekly',
            'collection_points' => [$point->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('collection_schedules', [
            'name' => 'Morning Commercial Route',
            'day_of_week' => 'monday',
            'frequency' => 'weekly',
            'barangay_id' => $this->barangay->id,
        ]);
    }

    public function test_bulk_delete_schedules()
    {
        $sched1 = CollectionSchedule::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'barangay_id' => $this->barangay->id,
            'day_of_week' => 'monday',
            'collection_time' => '08:00',
            'frequency' => 'weekly',
        ]);

        $sched2 = CollectionSchedule::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'barangay_id' => $this->barangay->id,
            'day_of_week' => 'tuesday',
            'collection_time' => '09:00',
            'frequency' => 'weekly',
        ]);

        $this->assertEquals(2, CollectionSchedule::count());

        $response = $this->actingAs($this->user)->post('/dashboard/schedules/bulk-delete', [
            'ids' => [$sched1->id, $sched2->id],
        ]);

        $response->assertRedirect();
        $this->assertEquals(0, CollectionSchedule::count());
    }

    /**
     * Test fleet views.
     */
    public function test_barangay_fleet_view()
    {
        $response = $this->actingAs($this->user)->get('/dashboard/fleet');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.partials.barangay.fleet');
    }
}
