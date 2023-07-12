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
            $table->string('phone')->nullable($value = true);
            $table->string('email')->nullable($value = true);
            $table->string('note')->nullable($value = true);
            $table->string('pickup')->nullable($value = true);
            $table->string('dropoff')->nullable($value = true);
            $table->integer('adultQuantity')->nullable($value = true);
            $table->integer('childrenQuantity')->nullable($value = true);
            $table->decimal('onlinePrice', $precision = 10, $scale = 2);/*Online price */
            $table->decimal('originalPrice', $precision = 10, $scale = 2);/*Original Price */
            $table->decimal('couponAmount', $precision = 10, $scale = 2)->nullable($value = true);/*Price after applying Coupon*/
            $table->decimal('finalPrice', $precision = 10, $scale = 2);/*Final price */
            $table->decimal('extraFee', $precision = 10, $scale = 2)->nullable($value = true);/*Extra fee */
            $table->dateTime('bookingDate', $precision = 0);
            $table->tinyInteger('paymentMethod')->nullable($value = true);
            $table->tinyInteger('paymentStatus')->default(0)->nullable($value = true);
            $table->string('transactionCode')->nullable($value = true);
            $table->dateTime('transactionDate', $precision = 0)->nullable($value = true);
            $table->tinyInteger('status')->default(0); /* 0=>Book, 9=>Already paid */
            $table->timestamps();

            //ADD Index
            $table->index('customerType');
            $table->index('userId');
            $table->index('agentId');
            $table->index('promotionId');
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
