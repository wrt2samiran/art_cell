<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderedSharedServiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('ordered_shared_service_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->index()->comment = 'order_id=shared service order id';
            $table->unsignedBigInteger('shared_service_id')->index();
            $table->integer('no_of_days');
            
            $table->integer('quantity');
            $table->double('price', 8, 2);

            $table->integer('no_of_extra_days');
            $table->double('extra_days_price', 8, 2);
            $table->integer('total_days')->comment = 'no_of_extra_days+number_of_days';

            $table->double('total_unit_price', 8, 2)->comment = 'price+extra_days_price';
            
            $table->double('total_price', 8, 2)->comment = 'total_unit_price*quantity';
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')->on('shared_service_orders')
                ->onDelete('cascade');

            $table->foreign('shared_service_id')
                ->references('id')->on('shared_services')
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
        Schema::dropIfExists('ordered_shared_servcie_details');
        Schema::enableForeignKeyConstraints();
    }
}
