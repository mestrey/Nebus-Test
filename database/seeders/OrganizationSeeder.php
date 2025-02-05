<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = Organization::factory(20)->create();

        foreach ($organizations as $organization) {
            $randomActivities = Activity::inRandomOrder()->limit(rand(1, 5))->pluck('id');
            $organization->activities()->attach($randomActivities);
        }
    }
}
