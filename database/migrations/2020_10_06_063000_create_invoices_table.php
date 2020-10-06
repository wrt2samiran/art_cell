<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id')->index()->comment = 'system generated';

            $table->enum('invoice_for', ['wo', 'sso', 'spo'])->nullable()->index()->comment = 'wo=Work Order/sso=Shared service order/spo=Spare parts order';
            $table->unsignedBigInteger('shared_service_order_id')->index()->nullable();
            $table->unsignedBigInteger('spare_part_order_id')->index()->nullable();
            $table->unsignedBigInteger('work_order_id')->index()->nullable();
            $table->date('invoice_date')->index();
            $table->date('customer_id')->index();
            $table->double('total_amount', 8, 2)->index();
            $table->string('currency',10)->index();
            $table->timestamps();
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
        Schema::dropIfExists('invoices');
        Schema::enableForeignKeyConstraints();
    }
}
