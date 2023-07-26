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
        if(!Schema::hasTable('rides')){
            Schema::create('rides', function (Blueprint $table) {
                $table->id('id');
                $table->string('name');
                $table->string('fromLocation');
                $table->string('toLocation');
                $table->string('departTime');
                $table->string('returnTime');
                $table->date('departDate');
                $table->integer('hoursBeforeBooking')->default(0);/* 0=>Anytime*/
                $table->tinyInteger('status')->default(0); /* 0=>Available, 1=>unAvailable */

                $table->timestamps();

                //ADD Index
                $table->index('fromLocation');
                $table->index('toLocation');
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
        Schema::dropIfExists('rides');
    }
};
