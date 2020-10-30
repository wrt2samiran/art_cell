<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('contract_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id')->index();
            $table->unsignedBigInteger('service_id')->index();

            $table->string('service_type',20)->index()->comment = 'i.e General/Maintenance/Free/On Demand etc';

            $table->string('currency',10);
            $table->double('price', 8, 2)->index();
            $table->unsignedBigInteger('frequency_type_id')->index()->nullable();
            $table->integer('interval_days')->nullable()->comment = '365 for 1 year, 30 for 1 month';

            $table->string('custom_frequency',100)->nullable()->comment ='If want to give custom frequency type like Every 50 days/Every 20 days instead of yearly/monthly/weekly';

            $table->integer('number_of_time_can_used')->nullable()->comment = 'this will applicable for On Demand servcie. Admin can limit the usage . null means unlimited usage';

            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();

            $table->foreign('contract_id')
              ->references('id')->on('contracts')
              ->onDelete('cascade');
            $table->foreign('service_id')
              ->references('id')->on('services')
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
        Schema::dropIfExists('contract_services');
        Schema::enableForeignKeyConstraints();
    }
}
