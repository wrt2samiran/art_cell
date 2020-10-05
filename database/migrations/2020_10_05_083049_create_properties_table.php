<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',30)->index()->comment = 'System generated';
            $table->string('property_name')->index();
            $table->text('description');
            $table->unsignedBigInteger('city_id')->index();
            $table->text('address');
            $table->text('location');
            $table->integer('no_of_units');
            $table->date('water_acount_date')->nullable();
            $table->date('electricy_account_date')->nullable();
            $table->unsignedBigInteger('property_owner')->index()->nullable();
            $table->unsignedBigInteger('property_manager')->index()->nullable();
            $table->boolean('is_active')->index()->default(true);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes(0);
            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')->on('cities');

            $table->foreign('property_owner')
                ->references('id')->on('users'); 
            $table->foreign('property_manager')
                ->references('id')->on('users');  
            $table->foreign('created_by')
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
        Schema::dropIfExists('properties');
    }
}
