<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_installments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id')->index();
            $table->double('price', 8, 2);
            $table->string('currency',10);
            $table->date('due_date')->index();
            $table->date('pre_notification_date')->index();
            $table->boolean('is_paid')->default(false);
            $table->date('paid_on')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes(0);
            $table->timestamps();

            $table->foreign('contract_id')
                ->references('id')->on('contracts');

            $table->foreign('created_by')
                ->references('id')->on('users');
            $table->foreign('updated_by')
                ->references('id')->on('users');
            $table->foreign('deleted_by')
                ->references('id')->on('users');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_installments');
    }
}
