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
        if(!Schema::hasTable('promotions')){
            Schema::create('promotions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code');
                $table->integer('quantity')->default(0)->nullable($value = true);
                $table->decimal('discount', $precision = 5, $scale = 2);
                $table->date('fromDate');
                $table->date('toDate');
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
        Schema::dropIfExists('promotions');
    }
};
