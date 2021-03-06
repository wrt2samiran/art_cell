<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name',100)->index();
            $table->string('last_name',100)->index();
            $table->string('email',150)->index();
            $table->string('contact_number',25)->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('country_id')->index();
            $table->unsignedBigInteger('state_id')->index();
            $table->unsignedBigInteger('city_id')->index();
            $table->string('landmark');
            $table->text('details');
            $table->text('contract_duration')->comment = 'In days';
            $table->unsignedBigInteger('frequency_type_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')
                ->references('id')->on('countries');
            $table->foreign('state_id')
                ->references('id')->on('states');
            $table->foreign('city_id')
                ->references('id')->on('cities');
            $table->foreign('frequency_type_id')
                ->references('id')->on('frequency_types');

        
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
        Schema::dropIfExists('quotations');
        Schema::enableForeignKeyConstraints();
    }
}
