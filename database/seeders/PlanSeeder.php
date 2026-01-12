<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name' => 'Free',
                'free_units' => 100,
                'price_per_unit' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Starter',
                'free_units' => 1000,
                'price_per_unit' => 0.05,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business',
                'free_units' => 10000,
                'price_per_unit' => 0.03,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'free_units' => 50000,
                'price_per_unit' => 0.01,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
