<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('NIK', 32);
            $table->string('cust_name', 255);
            $table->integer('price_each');
            $table->boolean('is_chose_seat');
            $table->foreignId('seats_id')->constrained()->onDelete('cascade');
            $table->foreignId('transactions_id')->constrained()->onDelete('cascade');
            $table->foreignId('cars_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('tickets');
    }
}
