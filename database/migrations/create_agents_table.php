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
        if(!Schema::hasTable('agents')){
            Schema::create('agents', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->tinyInteger('agentType');
                $table->string('code')->nullable($value = true);
                $table->tinyInteger('type')->nullable($value = true);
                $table->string('manager')->nullable($value = true);
                $table->string('email')->nullable($value = true);
                $table->string('phone')->nullable($value = true);
                $table->string('line')->nullable($value = true);
                $table->string('location')->nullable($value = true);
                $table->tinyInteger('paymentType')->nullable($value = true);
                $table->tinyInteger('agentContractType')->nullable($value = true);
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
        Schema::dropIfExists('agents');
    }
};
