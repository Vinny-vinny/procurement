<?php

use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Priority::create(['name' => 'High']);
        \App\Priority::create(['name' => 'Low']);
        \App\Priority::create(['name' => 'Medium']);
    }
}
