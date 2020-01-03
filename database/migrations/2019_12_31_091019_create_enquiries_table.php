<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('enquiry_no');
            $table->date('enquiry_date');
            $table->string('title');
            $table->string('description');
            $table->string('supplier_id');
            $table->date('req_date_from');
            $table->date('req_date_to');
            $table->string('item_type');
            $table->text('item_stock')->nullable();
            $table->text('item_asset')->nullable(); 
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
        Schema::dropIfExists('enquiries');
    }
}
