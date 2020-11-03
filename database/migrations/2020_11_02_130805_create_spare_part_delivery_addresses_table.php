<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparePartDeliveryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spare_part_delivery_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->index();
            $table->foreign('order_id')
                ->references('id')
                ->on('spare_part_orders')
                ->onDelete('cascade');
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('contact_number')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->unsignedBigInteger('country_id')->index();
            $table->unsignedBigInteger('state_id')->index();
            $table->unsignedBigInteger('city_id')->index();
            $table->string('pincode')->nullable();
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
        Schema::dropIfExists('spare_part_delivery_addresses');
    }
}
