<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('digital_input_id')->default(0);
            $table->foreignId('modbus_id')->default(0);
            $table->string('val')->nullable();
            $table->string('ket')->nullable();
            $table->integer('is_used')->default(0);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('temporaries');
    }
}
