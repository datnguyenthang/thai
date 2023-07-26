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
        if(!Schema::hasTable('users')){
            Schema::create('users', function (Blueprint $table) {
                $table->id('id');
                $table->tinyInteger('role')->default(0); /* Users: 0=>User, 1=>Super Admin, 2=>Manager, 3: moderator, 4: agent */
                $table->string('name');
                $table->integer('agentId')->nullable();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->tinyInteger('status')->default(0); /* 0=>Available, 1=>unAvailable */

                $table->rememberToken();
                $table->timestamps();

                //ADD Index
                $table->index('agentId');
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
        Schema::dropIfExists('users');
    }
};
