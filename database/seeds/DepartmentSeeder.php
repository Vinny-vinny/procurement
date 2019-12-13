<?php

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Department::create(['name' => 'ICT','description' => 'ICT']);
        \App\Department::create(['name' => 'PROCUREMENT','description' => 'PROCUREMENT']);
        \App\Department::create(['name' => 'ACCOUNTS','description' => 'ACCOUNTS']);
    }
}
