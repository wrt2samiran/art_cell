<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityAvailableColumnToSparePartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->integer('quantity_available')->default('1')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->dropColumn(['quantity_available']);
        });
    }
}
