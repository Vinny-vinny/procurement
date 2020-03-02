<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersSeeder::class);
         $this->call(CategorySeeder::class);
         $this->call(DepartmentSeeder::class);
         $this->call(ItemTypeSeeder::class);
         $this->call(PrioritySeeder::class);
         $this->call(RequisitionTypeSeeder::class);
         $this->call(CustomerTypesSeeder::class);
         $this->call(Uomseeder::class);
         $this->call(ServicesSeeder::class);
         $this->call(PaymentModeSeeder::class);


    }
}
