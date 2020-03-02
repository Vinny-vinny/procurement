<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetDisposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_disposals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('asset_details');
            $table->date('opening_date');
            $table->date('deadline_date'); 
            $table->string('disposal_no');      
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
        Schema::dropIfExists('asset_disposals');
    }
}
