<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('to_user')->index();
            $table->unsignedBigInteger('from_user')->index();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('to_user')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('from_user')
                ->references('id')->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('contact_messages');
        Schema::enableForeignKeyConstraints();
    }
}
