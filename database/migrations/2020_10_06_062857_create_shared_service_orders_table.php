<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('shared_service_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('order_currency',10);
            $table->double('total_amount', 8, 2)->index();
            $table->integer('tax_percentage');
            $table->double('tax_amount', 8, 2);
            $table->double('delivery_charge', 8, 2);
            $table->boolean('is_paid')->default(false);
            $table->datetime('paid_on')->index()->nullable();
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_service_orders');
    }
}
