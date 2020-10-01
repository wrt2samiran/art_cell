<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parrent_id')->nullable();
            $table->string('role_name');
            $table->string('slug');
            $table->longText('role_description');
            $table->enum('status', ['A', 'I', 'D'])->comment = 'A-active,I-inactive,D-delete';
            $table->timestamps();
            $table->bigInteger('created_by')->length(20);
            $table->bigInteger('updated_by')->length(20);
            $table->enum('is_deleted', ['Y', 'N'])->default('N')->comment = 'Y-yes,N-no';
            $table->bigInteger('deleted_by')->length(20)->nullable();
            
            $table->softDeletes('deleted_at', 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
