<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModbusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modbuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('merge_id')->default(0);
            $table->integer('id_modbus')->nullable();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('val')->nullable();
            $table->string('math')->default('x,');
            $table->string('after')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('is_used')->default(0);
            $table->integer('is_showed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modbuses');
    }
}
