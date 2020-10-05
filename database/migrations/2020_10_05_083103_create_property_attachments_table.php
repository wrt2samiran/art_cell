<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('property_id')->index();
            $table->string('file_name');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('property_id')
                ->references('id')->on('properties')
                ->onDelete('cascade');

            $table->foreign('created_by')
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
        Schema::dropIfExists('property_attachments');
    }
}
