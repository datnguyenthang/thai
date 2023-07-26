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
        if(!Schema::hasTable('seat_classes')){
            Schema::create('seat_classes', function (Blueprint $table) {
                $table->id();
                $table->integer('rideId');
                $table->string('name');
                $table->integer('capacity');
                $table->decimal('price', $precision = 10, $scale = 2);
                $table->tinyInteger('status')->default(0); /* 0=>Available, 1=>unAvailable */
                $table->timestamps();

                //ADD Index
                $table->index('rideId');
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
        Schema::dropIfExists('seat_classes');
    }
};
