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
        if(!Schema::hasTable('customer_types')){
            Schema::create('customer_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code');
                $table->tinyInteger('type')->default(0);
                $table->integer('price');
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
        Schema::dropIfExists('customer_types');
    }
};
