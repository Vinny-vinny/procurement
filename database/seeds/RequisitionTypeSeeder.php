<?php

use Illuminate\Database\Seeder;

class RequisitionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\RequisitionType::create(['name' => 'Internal']);
        \App\RequisitionType::create(['name' => 'External']);
    }
}
