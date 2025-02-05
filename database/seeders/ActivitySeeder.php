<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roots = Activity::factory(5)->create();

        foreach ($roots as $root) {
            $children = Activity::factory(3)->child($root->id)->create();

            foreach ($children as $child) {
                Activity::factory(2)->grandchild($child->id)->create();
            }
        }
    }
}
