<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfqResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('SupplierId');
            $table->date('Date');
            $table->string('Item');
            $table->string('Quantity');
            $table->string('Description');
            $table->float('Price');
            $table->enum('Currency',['KSH'=>'KSH', 'USD'=>'USD'])->default('KSH');
            $table->date('ExpectedDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfq_responses');
    }
}
