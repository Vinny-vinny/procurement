<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('RFQ_No');
            $table->string('Item');
            $table->string('Description');
            $table->integer('SupllierId');
            $table->string('Quatity');
            $table->date('ExpectedDate');
            $table->float('ExpectedPrice', '8', '2');
            $table->float('ActualPrice', '8', '2');
            $table->string('project');
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
        Schema::dropIfExists('rfqs');
    }
}
