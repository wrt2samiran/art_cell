<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id')->index();
            $table->unsignedBigInteger('service_id')->index();
            $table->timestamps();

            $table->foreign('contract_id')
              ->references('id')->on('contracts')
              ->onDelete('cascade');
            $table->foreign('service_id')
              ->references('id')->on('services')
              ->onDelete('cascade');
              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_service');
    }
}
