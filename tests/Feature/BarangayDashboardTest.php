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

    /**
     * Test storing a collection schedule.
     */
    public function test_store_collection_schedule()
    {
        $response = $this->actingAs($this->user)->post('/dashboard/schedules', [
            'day_of_week' => 'monday',
            'collection_time' => '08:00',
            'frequency' => 'weekly',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('collection_schedules', [
            'day_of_week' => 'monday',
            'frequency' => 'weekly',
            'barangay_id' => $this->barangay->id,
        ]);
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
