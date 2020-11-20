<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('contract_id')->index()->nullable();
            $table->unsignedBigInteger('task_id')->index()->nullable();
            $table->text('details');
            $table->unsignedBigInteger('complaint_status_id')->index()->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->timestamps();

            $table->foreign('contract_id')
                ->references('id')->on('contracts')
                ->onDelete('cascade');

            $table->foreign('last_updated_by')
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
        Schema::dropIfExists('complaints');
        Schema::enableForeignKeyConstraints();
    }
}
