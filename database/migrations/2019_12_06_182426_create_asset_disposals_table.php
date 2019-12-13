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
            $table->string('Propertyof');
            $table->date('DisposalDate');
            $table->text('DisposalReason');
            $table->text('ItemDescription');
            $table->integer('AssetId');
            $table->integer('SerialNo');
            $table->integer('DepartmentId');
            $table->enum('DisposalMethod', ['Trade-In', 'Sale', 'Junk', 'Other']);
            $table->integer('Trade-In')->nullable();
            $table->integer('Sale')->nullable();
            $table->integer('Junk')->nullable();
            $table->integer('Other')->nullable();
            $table->string('SoldTo');
            $table->string('sign');
            $table->float('Amount','10', '2');
            $table->string('PaymentTerms');
            $table->string('CharmanProcurement');
            $table->string('FinanceDept');
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
