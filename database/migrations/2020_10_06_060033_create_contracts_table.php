<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',30)->comment = 'System generated';
            $table->text('description');
            $table->unsignedBigInteger('customer_id')->index()->nullable();
            $table->unsignedBigInteger('property_id')->index();
            $table->unsignedBigInteger('service_provider_id')->index()->nullable();
            $table->date('start_date')->index();
            $table->date('end_date')->index();
            $table->double('contract_price', 8, 2)->index();
            $table->string('contract_price_currency',10);
            $table->boolean('is_paid')->default(true);
            $table->datetime('paid_on')->nullable();
            $table->boolean('in_installment')->default(false);
            $table->integer('notify_installment_before_days')->nullable();
            $table->boolean('is_active')->index()->default(true);
            $table->string('status',30)->index()->default('active');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('customer_id')
                ->references('id')->on('users');
            $table->foreign('property_id')
                ->references('id')->on('properties');
            $table->foreign('service_provider_id')
                ->references('id')->on('users');

            $table->foreign('created_by')
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
        Schema::dropIfExists('contracts');
        Schema::enableForeignKeyConstraints();
    }
}
