<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('contract_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id')->index();
            $table->string('file_name');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('contract_id')
                ->references('id')->on('contracts')
                ->onDelete('cascade');

            $table->foreign('created_by')
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
        Schema::dropIfExists('contract_attachments');
        Schema::enableForeignKeyConstraints();
    }
}
