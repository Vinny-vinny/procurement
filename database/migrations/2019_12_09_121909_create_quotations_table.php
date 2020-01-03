<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('quote_no');
            $table->string('item_type');
            $table->date('quote_date');
            $table->integer('supplier_id');
            $table->integer('enquiry_id');
            $table->string('description');
            $table->string('file')->nullable();
            $table->string('reject_reason')->nullable();
            $table->integer('status')->nullable();     
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
        Schema::dropIfExists('quotations');
    }
}
