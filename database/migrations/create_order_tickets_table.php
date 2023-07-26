<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('locations')){
            Schema::create('order_tickets', function (Blueprint $table) {
                $table->id();
                $table->integer('orderId');
                $table->integer('rideId');
                $table->string('code');
                $table->integer('seatClassId');
                $table->tinyInteger('type');/* 1=>Departure, 2=>Return */
                $table->decimal('price', $precision = 10, $scale = 2);
                $table->tinyInteger('status')->default(0); /* 0=>Book, 9=>Already paid */
                $table->timestamps();

                //ADD Index
                $table->index('orderId');
                $table->index('rideId');
                $table->index('seatClassId');
            });
        }
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
};
