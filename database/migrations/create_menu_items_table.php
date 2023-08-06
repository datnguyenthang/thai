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
        if(!Schema::hasTable('menu_items')){
            Schema::create('menu_items', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('url');
                $table->unsignedBigInteger('page_id')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->tinyInteger('isOpenNewTab')->default(0);
                $table->unsignedBigInteger('sortOrder')->nullable();
                $table->tinyInteger('status')->default(0); /* 0=>Available, 1=>unAvailable */
                $table->timestamps();

                //$table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
};
