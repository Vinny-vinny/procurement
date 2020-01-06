<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id');
            $table->bigInteger('total_amount');
            $table->date('begins_on');
            $table->date('ends_on');
            $table->string('item_type');
            $table->text('item_stock')->nullable();
            $table->text('item_asset')->nullable();
            $table->string('renewal_type')->nullable();
            $table->string('frequency_type')->nullable();
            $table->integer('period')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('department_budgets');
    }
}
