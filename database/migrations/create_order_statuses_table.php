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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('orderId');
            $table->integer('status');
            $table->text('note')->nullable($value = true);
            $table->dateTime('changeDate', $precision = 0);
            $table->integer('userId')->nullable($value = true);
            
            $table->timestamps();

            //ADD Index
            $table->index('orderId');
            $table->index('userId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_status');
    }
};
