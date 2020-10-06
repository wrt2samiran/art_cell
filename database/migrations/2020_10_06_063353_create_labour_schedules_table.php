<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabourSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('labour_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->unsignedBigInteger('mta_id');
            $table->foreign('mta_id')->references('id')->on('maintenance_task_assigns')->onDelete('cascade');
            $table->unsignedBigInteger('labour_id');
            $table->foreign('labour_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('service_start_date');
            $table->date('service_end_date');
            $table->text('labour_feedback');
            $table->enum('status', ['A', 'I', 'D'])->comment = 'A-active,I-inactive,D-delete';
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->enum('is_deleted', ['Y', 'N'])->default('N')->comment = 'Y-yes,N-no';
            $table->bigInteger('deleted_by')->nullable();
            $table->softDeletes('deleted_at', 0)->nullable(); 
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
        Schema::dropIfExists('labour_schedules');
        Schema::enableForeignKeyConstraints();
    }
}
