<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedSparePartDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordered_spare_part_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('spare_part_order_id')->index();
            $table->unsignedBigInteger('spare_part_id')->index();
            $table->integer('quantity');
            $table->double('price', 8, 2);
            $table->double('total_price', 8, 2)->comment = 'price multiplied by quantity';
            $table->timestamps();

            $table->foreign('spare_part_order_id')
                ->references('id')->on('spare_part_orders')
                ->onDelete('cascade');

            $table->foreign('spare_part_id')
                ->references('id')->on('spare_parts')
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
        Schema::dropIfExists('ordered_spare_part_details');
    }
}
