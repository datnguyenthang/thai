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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('agentType');
            $table->string('code');
            $table->tinyInteger('type');
            $table->string('manager');
            $table->string('email');
            $table->string('phone');
            $table->string('line')->nullable($value = true);;
            $table->tinyInteger('paymentType');
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
        Schema::dropIfExists('agents');
    }
};
