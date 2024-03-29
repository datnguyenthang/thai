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
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('nameOffice');
                $table->string('googleMapUrl');
                $table->tinyInteger('status')->default(0); /* 0=>Available, 1=>unAvailable */
                $table->timestamps();
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
        Schema::dropIfExists('locations');
    }
};
