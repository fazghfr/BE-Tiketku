<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('car_count');
            $table->boolean('is_cafe')->default(true);
            $table->boolean('is_toilet')->default(true);
            $table->boolean('is_tv')->default(true);
            $table->boolean('is_wifi')->default(true);
            $table->integer('seat_per_car_count');
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
        Schema::dropIfExists('trains');
    }
}
