<?php

use Illuminate\Database\Seeder;
use App\PaymentMode;

class PaymentModeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMode::create(['name' =>'Mpesa','description'=>'Mpesa']);
        PaymentMode::create(['name' =>'Credit Card','description'=>'Credit Card']);
        PaymentMode::create(['name' =>'Paypal','description'=>'Paypal']);
    }
}
