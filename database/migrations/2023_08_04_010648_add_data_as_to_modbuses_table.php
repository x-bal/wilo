<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataAsToModbusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modbuses', function (Blueprint $table) {
            $table->enum('data_as', ['running', 'trip', 'stop'])->default('running');
            $table->char('point_one')->nullable();
            $table->char('point_two')->nullable();
            $table->text('notif_one')->nullable();
            $table->text('notif_two')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modbuses', function (Blueprint $table) {
            $table->dropColumn('data_as');
            $table->dropColumn('point_one');
            $table->dropColumn('point_two');
            $table->dropColumn('notif_one');
            $table->dropColumn('notif_two');
        });
    }
}
