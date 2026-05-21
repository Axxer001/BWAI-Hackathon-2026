<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Barangays ──────────────────────────────────────────────────────
        $this->call(BarangaySeeder::class);

        // Pick 5 barangays to work with
        $barangays = DB::table('barangays')->limit(5)->get();

        $now = now();

        // ── 2. Users ──────────────────────────────────────────────────────────
        // Admin
        $adminId = (string) Str::uuid();
        DB::table('users')->insertOrIgnore([
            'id'         => $adminId,
            'full_name'  => 'System Administrator',
            'email'      => 'admin@limpioZambo.ph',
            'phone'      => '09100000001',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'barangay_id'=> null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Barangay managers (one per barangay)
        $barangayUserIds = [];
        foreach ($barangays as $i => $b) {
            $uid = (string) Str::uuid();
            $barangayUserIds[$b->id] = $uid;
            DB::table('users')->insertOrIgnore([
                'id'         => $uid,
                'full_name'  => "Barangay Manager {$b->name}",
                'email'      => 'manager' . ($i + 1) . '@limpioZambo.ph',
                'phone'      => '0910000' . str_pad($i + 10, 4, '0', STR_PAD_LEFT),
                'password'   => Hash::make('password'),
                'role'       => 'barangay',
                'barangay_id'=> $b->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Assign manager to barangay
            DB::table('barangays')->where('id', $b->id)->update(['managed_by' => $uid]);
        }

        // Collectors (2 per barangay)
        $collectorIds = [];
        foreach ($barangays as $i => $b) {
            for ($c = 1; $c <= 2; $c++) {
                $uid = (string) Str::uuid();
                $collectorIds[$b->id][] = $uid;
                DB::table('users')->insertOrIgnore([
                    'id'         => $uid,
                    'full_name'  => "Collector {$c} - " . explode(' ', $b->name)[1] ?? $b->name,
                    'email'      => "collector{$i}{$c}@limpioZambo.ph",
                    'phone'      => '0920000' . str_pad($i * 2 + $c, 4, '0', STR_PAD_LEFT),
                    'password'   => Hash::make('password'),
                    'role'       => 'collector',
                    'barangay_id'=> $b->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Residents (5 per barangay)
        $residentIds = [];
        $residentNames = [
            'Maria Santos', 'Juan dela Cruz', 'Ana Reyes', 'Pedro Garcia', 'Rosa Mendoza',
            'Carlo Bautista', 'Lina Aquino', 'Rico Villanueva', 'Mila Flores', 'Ben Castillo',
            'Luz Morales', 'Rey Navarro', 'Elena Pascual', 'Danny Ramos', 'Cora Salazar',
            'Felix Torres', 'Alma Vega', 'Nick Medina', 'Tess Aguilar', 'Joel Espinosa',
            'Nora Guevarra', 'Roel Lim', 'Gina Perez', 'Mark Soriano', 'Beth Tomas',
        ];
        $ri = 0;
        foreach ($barangays as $i => $b) {
            $residentIds[$b->id] = [];
            for ($r = 0; $r < 5; $r++) {
                $uid = (string) Str::uuid();
                $residentIds[$b->id][] = $uid;
                DB::table('users')->insertOrIgnore([
                    'id'         => $uid,
                    'full_name'  => $residentNames[$ri] ?? "Resident {$ri}",
                    'email'      => 'resident' . $ri . '@limpioZambo.ph',
                    'phone'      => '0930000' . str_pad($ri, 4, '0', STR_PAD_LEFT),
                    'password'   => Hash::make('password'),
                    'role'       => 'user',
                    'barangay_id'=> $b->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $ri++;
            }
        }

        // ── 3. Trucks (2 per barangay) ────────────────────────────────────────
        $truckIds = [];
        foreach ($barangays as $i => $b) {
            for ($t = 1; $t <= 2; $t++) {
                $tid = (string) Str::uuid();
                $truckIds[$b->id][] = $tid;
                DB::table('trucks')->insertOrIgnore([
                    'id'            => $tid,
                    'barangay_id'   => $b->id,
                    'plate_number'  => 'ZC-' . str_pad($i * 2 + $t, 4, '0', STR_PAD_LEFT),
                    'capacity_tons' => [2.5, 3.0, 4.0, 5.0][($i + $t) % 4],
                    'is_active'     => true,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }

        // ── 4. Garbage Points (3 per barangay) ───────────────────────────────
        // Real-ish coordinates around Zamboanga City (6.9214, 122.0790)
        $latBase  = 6.9214;
        $lngBase  = 122.0790;
        $pointNames = ['Main Street Corner', 'Market Area', 'Covered Court', 'Barangay Hall', 'Elementary School'];

        $garbagePointIds = [];
        foreach ($barangays as $i => $b) {
            $garbagePointIds[$b->id] = [];
            for ($p = 0; $p < 3; $p++) {
                $pid = (string) Str::uuid();
                $garbagePointIds[$b->id][] = $pid;
                DB::table('garbage_points')->insertOrIgnore([
                    'id'          => $pid,
                    'name'        => ($pointNames[$p] ?? "Point {$p}") . " - {$b->name}",
                    'latitude'    => round($latBase + ($i * 0.005) + ($p * 0.002), 7),
                    'longitude'   => round($lngBase + ($i * 0.005) + ($p * 0.002), 7),
                    'address'     => "Zone {$p}, {$b->name}, Zamboanga City",
                    'barangay_id' => $b->id,
                    'is_active'   => true,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        // ── 5. Assign Residents to Points ────────────────────────────────────
        foreach ($barangays as $b) {
            $points    = $garbagePointIds[$b->id] ?? [];
            $residents = $residentIds[$b->id] ?? [];
            foreach ($residents as $ri => $uid) {
                $pointId = $points[$ri % count($points)];
                DB::table('user_point_assignments')->insertOrIgnore([
                    'id'               => (string) Str::uuid(),
                    'user_id'          => $uid,
                    'garbage_point_id' => $pointId,
                    'is_active'        => true,
                    'assigned_at'      => $now,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);
            }
        }

        // ── 6. Collection Schedules (per barangay) ───────────────────────────
        $days      = ['monday', 'wednesday', 'friday'];
        $scheduleIds = [];
        foreach ($barangays as $i => $b) {
            $sid = (string) Str::uuid();
            $scheduleIds[$b->id] = $sid;
            DB::table('collection_schedules')->insertOrIgnore([
                'id'              => $sid,
                'barangay_id'     => $b->id,
                'day_of_week'     => $days[$i % count($days)],
                'collection_time' => '07:00:00',
                'frequency'       => 'weekly',
                'is_active'       => true,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        // ── 7. Collection Sessions (last 7 days) ──────────────────────────────
        $statuses = ['completed', 'completed', 'ongoing', 'pending', 'cancelled'];
        foreach ($barangays as $i => $b) {
            $collectorId = $collectorIds[$b->id][0] ?? null;
            $truckId     = $truckIds[$b->id][0] ?? null;
            $scheduleId  = $scheduleIds[$b->id] ?? null;
            if (!$collectorId || !$truckId || !$scheduleId) continue;

            for ($d = 0; $d < 5; $d++) {
                $status    = $statuses[$d];
                $sessionDate = Carbon::now()->subDays($d)->toDateString();
                $startedAt   = $status !== 'pending' ? Carbon::now()->subDays($d)->setTime(7, 0) : null;
                $endedAt     = $status === 'completed' ? Carbon::now()->subDays($d)->setTime(10, 30) : null;

                DB::table('collection_sessions')->insertOrIgnore([
                    'id'           => (string) Str::uuid(),
                    'barangay_id'  => $b->id,
                    'schedule_id'  => $scheduleId,
                    'collector_id' => $collectorId,
                    'truck_id'     => $truckId,
                    'session_date' => $sessionDate,
                    'status'       => $status,
                    'started_at'   => $startedAt,
                    'ended_at'     => $endedAt,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ]);
            }
        }

        $this->command->info('✅ Database seeded: barangays, users, trucks, points, schedules, sessions.');
    }
}
