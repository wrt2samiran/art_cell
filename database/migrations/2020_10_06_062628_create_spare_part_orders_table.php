<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparePartOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::disableForeignKeyConstraints();
      Schema::create('spare_part_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('order_currency',10);
            $table->double('total_amount', 8, 2)->index();
            $table->integer('tax_percentage');
            $table->double('tax_amount', 8, 2);
            $table->double('delivery_charge', 8, 2);
            $table->boolean('is_paid')->default(false);
            $table->datetime('paid_on')->nullable();
            $table->boolean('is_accepted')->index()->default(true);
            $table->string('curent_status',50)->index()->default('received');
            $table->text('delivery_address_details')->nullable();
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->foreign('updated_by')
                ->references('id')->on('users');
            $table->foreign('deleted_by')
                ->references('id')->on('users');


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
        Schema::dropIfExists('spare_part_orders');
        Schema::enableForeignKeyConstraints();
    }
}
