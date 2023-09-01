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
        if(!Schema::hasTable('order_payments')){
            Schema::create('order_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('orderId');
                $table->tinyInteger('paymentMethod')->nullable($value = true);
                $table->tinyInteger('paymentStatus')->default(0)->nullable($value = true);
                $table->string('transactionCode')->nullable($value = true);
                $table->dateTime('transactionDate', $precision = 0)->nullable($value = true);
                $table->text('note')->nullable($value = true);
                $table->dateTime('changeDate', $precision = 0);
                $table->integer('userId')->nullable($value = true);
                
                $table->timestamps();

                //ADD Index
                $table->index('orderId');
                $table->index('userId');
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
        Schema::dropIfExists('order_payments');
    }
};
