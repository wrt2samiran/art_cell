<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyTypeQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_type_quotation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('property_type_id')->index();
            $table->unsignedBigInteger('quotation_id')->index();
            $table->timestamps();

            $table->foreign('property_type_id')
              ->references('id')->on('property_types')
              ->onDelete('cascade');
            $table->foreign('quotation_id')
              ->references('id')->on('quotations')
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
        Schema::dropIfExists('property_type_quotation');
    }
}
