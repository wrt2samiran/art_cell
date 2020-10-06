<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrequencyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('frequency_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type',20)->comment = 'Daily,Weekly,Monthly,Quaterly,Yearly';
            $table->string('slug',50);
            $table->text('description')->nullable();
            $table->integer('no_of_days')->comment = 'i.e 30 for monthly';
            $table->boolean('is_active')->index()->default(true);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('frequency_types');
        Schema::enableForeignKeyConstraints();
    }
}
