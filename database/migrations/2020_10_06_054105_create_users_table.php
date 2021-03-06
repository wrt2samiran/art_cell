<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('role_id')->length(20);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->string('userkey')->nullable();
            $table->enum('usertype', ['S', 'SA', 'FU'])->nullable()->comment = 'S=Superadmin SA=SubAdmin FU=Frontend User';
            $table->string('profile_pic')->nullable();
            $table->string('password')->nullable();
            $table->enum('status', ['A', 'I', 'D'])->comment = 'A-active,I-inactive,D-delete';
            $table->longText('setting_json')->nullable();
            $table->rememberToken();
            $table->string('api_token')->nullable();
            $table->enum('created_from', ['B', 'F'])->nullable()->comment = 'B= Backend , F = Frontednd';
            $table->timestamps();
            $table->bigInteger('created_by')->length(20);
            $table->bigInteger('updated_by')->nullable()->length(20);
            $table->enum('is_deleted', ['Y', 'N'])->default('N')->comment = 'Y-yes,N-no';
            $table->bigInteger('deleted_by')->length(20)->nullable();
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
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
}
