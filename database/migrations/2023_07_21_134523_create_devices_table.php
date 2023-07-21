<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->integer('iddev')->unique();
            $table->foreignId('company_id')->constrained('companies');
            $table->string('name');
            $table->string('end_user')->nullable();
            $table->string('type');
            $table->string('power');
            $table->string('head');
            $table->string('flow');
            $table->string('lat');
            $table->string('long');
            $table->string('digital')->nullable();
            $table->string('modbus')->nullable();
            $table->string('image')->nullable();
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('devices');
    }
}
