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
        Schema::create('pickupdropoffs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(0); /* 0=>pickup, 1=>dropoff */
            $table->string('name');
            $table->tinyInteger('status')->default(0); /* 0=>Available, 1=>unAvailable */
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
        Schema::dropIfExists('pickupdropoffs');
    }
};
