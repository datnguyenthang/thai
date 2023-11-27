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
        if(!Schema::hasTable('role_permissions')){
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->integer('roleId')->nullable();
                $table->integer('permissionId')->nullable();
                $table->tinyInteger('status')->default(0); /* 0=>Available, 1=>unAvailable */
                $table->timestamps();

                //ADD Index
                $table->index('roleId');
                $table->index('permissionId');
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
        Schema::dropIfExists('role_permissions');
    }
};
