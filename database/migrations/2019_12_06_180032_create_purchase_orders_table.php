<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('PurchaseOrderNo');
            $table->date('PurchaseOrderDate');
            $table->double('Amount');
            $table->integer('SupplierId');
            $table->text('Description');
            $table->string('TermsConditions');
            $table->date('EndDate');
            $table->string('QuotationId');
            $table->enum('IsArchieved', ['Achieved', 'Active'])->default('Active');
            $table->string('PONo');
            $table->integer('DirectPO');
            $table->string('CurrencyId');
            $table->integer('ExchangeRate');
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
        Schema::dropIfExists('purchase_orders');
    }
}
