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
        Schema::disableForeignKeyConstraints();
        Schema::create('properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',30)->index()->comment = 'System generated';
            $table->string('property_name')->index();
            $table->unsignedBigInteger('property_type_id')->index();
            $table->text('description');
            $table->unsignedBigInteger('country_id')->index();
            $table->unsignedBigInteger('state_id')->index();
            $table->unsignedBigInteger('city_id')->index();
            $table->text('address');
            $table->text('location');
            $table->string('contact_number',30)->nullable();
            $table->string('contact_email',150)->nullable();
            $table->integer('no_of_units')->nullable();
            $table->integer('water_account_day')->nullable();
            $table->integer('electricity_account_day')->nullable();
            $table->unsignedBigInteger('property_owner')->index()->nullable();
            $table->unsignedBigInteger('property_manager')->index()->nullable();
            $table->boolean('is_active')->index()->default(true);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')->on('countries');
            $table->foreign('state_id')
                ->references('id')->on('states');
            $table->foreign('city_id')
                ->references('id')->on('cities');

            $table->foreign('property_type_id')
                ->references('id')->on('property_types');

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
        Schema::dropIfExists('properties');
        Schema::enableForeignKeyConstraints();
    }
}
