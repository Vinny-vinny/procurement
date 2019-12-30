<?php

use Illuminate\Database\Seeder;
use App\Uom;
class Uomseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Uom::create(['name' => 'BOXES']);
        Uom::create(['name' => 'PCS']);
        Uom::create(['name' => 'PKTS']);
        Uom::create(['name' => 'RM']);
        Uom::create(['name' => 'UNIT']);
    }
}
