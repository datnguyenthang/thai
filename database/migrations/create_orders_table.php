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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('customerType');
            $table->integer('userId')->nullable($value = true);
            $table->tinyInteger('isReturn')->default(0); /* 0=>NO Return, 9=>Already Return */
            $table->integer('agentId')->nullable($value = true);
            $table->integer('promotionId')->nullable($value = true);
            $table->string('firstName');
            $table->string('lastName');
            $table->decimal('phone', $precision = 11, $scale = 0);
            $table->string('email');
            $table->string('note')->nullable($value = true);
            $table->integer('adultQuantity')->nullable($value = true);
            $table->integer('childrenQuantity')->nullable($value = true);
            $table->decimal('price', $precision = 10, $scale = 2);
            $table->dateTime('bookingDate', $precision = 0);
            $table->tinyInteger('status')->default(0); /* 0=>Book, 9=>Already paid */
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
};
