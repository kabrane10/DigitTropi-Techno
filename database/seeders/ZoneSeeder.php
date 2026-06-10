<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zone;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            ['name' => 'Centrale'],
            ['name' => 'Kara'],
            ['name' => 'Savanes'],
        ];

        foreach ($zones as $zone) {
            Zone::create($zone);
        }
    }
}
