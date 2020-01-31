<?php

use Illuminate\Database\Seeder;
use App\Service;
class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create(['name' => 'computer repair','description'=>'Simply repair','amount'=>7000]);
        Service::create(['name' => 'car hire','description'=>'Simply car hire','amount'=>1500]);
        Service::create(['name' => 'staff training','description'=>'Simply training','amount'=>20000]);
        Service::create(['name' => 'software maintenance','description'=>'Simply maintenance','amount'=>100000]);

    }
}
