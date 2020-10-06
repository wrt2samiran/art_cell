<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('template_for')->index();
            $table->text('content');
            $table->text('variable_fields')->comment = 'to store variables like USER_NAME';
            $table->timestamps();
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
        Schema::dropIfExists('sms_templates');
        Schema::enableForeignKeyConstraints();
    }
}
