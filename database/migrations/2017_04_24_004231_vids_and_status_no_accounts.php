<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VidsAndStatusNoAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->integer('idstate')->primary();
            $table->string('name')->unique();
        });

        Schema::create('vids', function (Blueprint $table) {
            $table->engine = "InnoDB";

            // laravel automatically sets increment columns as the primary key
            $table->increments('id');
            // the name is the vid file name, changing it will make the video unreachable
            $table->string('name')->unique(); 
            $table->integer('state')->default(0);

            $table->foreign('state')->references('idstate')->on('states')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vids');
        Schema::dropIfExists('states');
    }
}
