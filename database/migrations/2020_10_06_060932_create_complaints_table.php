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
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('contract_id')->index()->nullable();
            $table->unsignedBigInteger('work_order_id')->index()->nullable();
            $table->unsignedBigInteger('task_id')->index()->nullable();
            $table->text('details');

            $table->unsignedBigInteger('complaint_status_id')->index()->nullable();
            $table->string('status',30)->nullable();
            $table->text('note_from_admin_end')->nullable();
            $table->text('note_service_provider')->nullable();
            $table->text('note_property_owner_end')->nullable();

            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->timestamps();

            $table->foreign('contract_id')
                ->references('id')->on('contracts')
                ->onDelete('cascade');

            $table->foreign('last_updated_by')
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
        Schema::dropIfExists('complaints');
    }
}
