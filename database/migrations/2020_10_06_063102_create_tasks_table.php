<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id');
            $table->foreign('contarct_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->string('task_name');
            $table->enum('task_type', ['O', 'M'])->comment = 'O=>One Time, M=>Maintenance';
            $table->string('pre_reminder_frequency');
            $table->integer('maintenance_frequency');
            $table->date('reminder_date');
            $table->date('task_start_date');
            $table->integer('task_total_days');
            $table->date('task_end_date');
            $table->enum('status', ['A', 'I', 'D'])->comment = 'A-active,I-inactive,D-delete';
            $table->enum('po_continue_service', ['Y', 'N'])->default('N')->comment = 'Y-yes,N-no';
            $table->enum('admin_continue_service', ['Y', 'N'])->default('N')->comment = 'Y-yes,N-no';           
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
        Schema::dropIfExists('tasks');
        Schema::enableForeignKeyConstraints();
    }
}
