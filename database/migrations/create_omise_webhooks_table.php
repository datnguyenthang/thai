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
        if(!Schema::hasTable('omise_webhook_events')){
            Schema::create('omise_webhook_events', function (Blueprint $table) {
                $table->id();
                $table->string('eventType')->nullable();
                $table->string('orderCode')->nullable();
                $table->string('eventChargeid')->nullable();
                $table->string('eventStatus')->nullable();
                $table->json('eventData')->nullable();
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
        Schema::dropIfExists('omise_webhook_events');
    }
};
