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
        \App\ItemType::create(['name' => 'Stock Item']);
        \App\ItemType::create(['name' => 'Asset']);
    }
}
