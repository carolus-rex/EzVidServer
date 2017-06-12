<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropVidsIdstatesFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vids', function (Blueprint $table){
            $table->dropForeign(['state']);
            // All the other id columns are unsigned, so we should.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vids', function (Blueprint $table){
            $table->foreign('state')->references('idstate')->on('states')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
        });
    }
}
