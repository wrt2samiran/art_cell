<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('service_quotation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id')->index();
            $table->unsignedBigInteger('quotation_id')->index();
            $table->timestamps();

            $table->foreign('service_id')
              ->references('id')->on('services')
              ->onDelete('cascade');
            $table->foreign('quotation_id')
              ->references('id')->on('quotations')
              ->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('service_quotation');
        Schema::enableForeignKeyConstraints();
    }
}
