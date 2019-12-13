<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('req_no');
            $table->bigInteger('requisition_type_id');
            $table->datetime('req_date');
            $table->integer('department_id');
            $table->integer('project_id');
            $table->integer('item_type_id');
            $table->string('item_id');
            $table->integer('priority_id');
            $table->integer('requisition_by');
            $table->string('description');
            $table->string('is_approved')->nullable();
            $table->integer('modified_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('requisitions');
    }
}
