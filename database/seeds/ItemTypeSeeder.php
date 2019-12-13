<?php

use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ItemType::create(['name' => 'Construction']);
        \App\ItemType::create(['name' => 'For Sale']);
        \App\ItemType::create(['name' => 'For Hire']);
    }
}
