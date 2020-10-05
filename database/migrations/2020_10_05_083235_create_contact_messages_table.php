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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('to_user')->index();
            $table->unsignedBigInteger('from_user')->index();
            $table->text('message');
            $table->timestamps();

            $table->foreign('to_user')
                ->references('id')->on('users');
            $table->foreign('from_user')
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
        Schema::dropIfExists('contact_messages');
    }
}
