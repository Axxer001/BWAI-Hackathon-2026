<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        $barangays = [
            // District I
            ['name' => 'Barangay Ayala',             'district' => 'District I'],
            ['name' => 'Barangay Baliwasan',         'district' => 'District I'],
            ['name' => 'Barangay Boalan',            'district' => 'District I'],
            ['name' => 'Barangay Canelar',           'district' => 'District I'],
            ['name' => 'Barangay Cawit',             'district' => 'District I'],
            ['name' => 'Barangay Culianan',          'district' => 'District I'],
            ['name' => 'Barangay Guiwan',            'district' => 'District I'],
            ['name' => 'Barangay Labuan',            'district' => 'District I'],
            ['name' => 'Barangay Limpapa',           'district' => 'District I'],
            ['name' => 'Barangay Mampang',           'district' => 'District I'],
            ['name' => 'Barangay Manicahan',         'district' => 'District I'],
            ['name' => 'Barangay Muti',              'district' => 'District I'],
            ['name' => 'Barangay Pasonanca',         'district' => 'District I'],
            ['name' => 'Barangay San Roque',         'district' => 'District I'],
            ['name' => 'Barangay Talon-Talon',       'district' => 'District I'],
            ['name' => 'Barangay Talisayan',         'district' => 'District I'],
            ['name' => 'Barangay Tetuan',            'district' => 'District I'],
            ['name' => 'Barangay Tugbungan',         'district' => 'District I'],
            ['name' => 'Barangay Tumaga',            'district' => 'District I'],
            ['name' => 'Barangay Zambowood',         'district' => 'District I'],

            // District II
            ['name' => 'Barangay Arena Blanco',      'district' => 'District II'],
            ['name' => 'Barangay Cabaluay',          'district' => 'District II'],
            ['name' => 'Barangay Calarian',          'district' => 'District II'],
            ['name' => 'Barangay Campo Islam',       'district' => 'District II'],
            ['name' => 'Barangay Camput de Corregidor', 'district' => 'District II'],
            ['name' => 'Barangay Caqui',             'district' => 'District II'],
            ['name' => 'Barangay Divisoria',         'district' => 'District II'],
            ['name' => 'Barangay Dulian (Upper)',    'district' => 'District II'],
            ['name' => 'Barangay Dulian (Lower)',    'district' => 'District II'],
            ['name' => 'Barangay Kapingkong',        'district' => 'District II'],
            ['name' => 'Barangay La Paz',            'district' => 'District II'],
            ['name' => 'Barangay Latuan',            'district' => 'District II'],
            ['name' => 'Barangay Licomo',            'district' => 'District II'],
            ['name' => 'Barangay Lumayang',          'district' => 'District II'],
            ['name' => 'Barangay Lunzuran',          'district' => 'District II'],
            ['name' => 'Barangay Maasin',            'district' => 'District II'],
            ['name' => 'Barangay Mariki',            'district' => 'District II'],
            ['name' => 'Barangay Mercedes',          'district' => 'District II'],
            ['name' => 'Barangay Pamucutan',         'district' => 'District II'],
            ['name' => 'Barangay Recodo',            'district' => 'District II'],
            ['name' => 'Barangay Salaan',            'district' => 'District II'],
            ['name' => 'Barangay Santo Niño',        'district' => 'District II'],
            ['name' => 'Barangay Sibulao',           'district' => 'District II'],
            ['name' => 'Barangay Sinubung',          'district' => 'District II'],
            ['name' => 'Barangay Taluksangay',       'district' => 'District II'],
            ['name' => 'Barangay Tictapul',          'district' => 'District II'],
            ['name' => 'Barangay Tigbalabag',        'district' => 'District II'],
            ['name' => 'Barangay Tulungatung',       'district' => 'District II'],

            // City Proper / Poblacion
            ['name' => 'Barangay 1 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 2 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 3 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 4 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 5 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 6 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 7 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 8 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 9 (Poblacion)',     'district' => 'City Proper'],
            ['name' => 'Barangay 10 (Poblacion)',    'district' => 'City Proper'],
        ];

        $now = now();

        foreach ($barangays as $barangay) {
            DB::table('barangays')->insertOrIgnore([
                'id'         => (string) Str::uuid(),
                'name'       => $barangay['name'],
                'district'   => $barangay['district'],
                'city'       => 'Zamboanga City',
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('✅ ' . count($barangays) . ' Zamboanga City barangays seeded.');
    }
}
