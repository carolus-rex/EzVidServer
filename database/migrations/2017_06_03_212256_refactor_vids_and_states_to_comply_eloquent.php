<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorVidsAndStatesToComplyEloquent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('states', function (Blueprint $table){
            // All the other id columns are unsigned, so we should.
            $table->integer('idstate')->unsignedInteger()->change();
            
            $table->renameColumn('idstate', 'id');
        });

        Schema::table('vids', function (Blueprint $table){
            // All the other id columns are unsigned, so we should.
            $table->integer('state')->unsignedInteger()->change();

            $table->renameColumn('state', 'state_id');
            
            $table->foreign('state_id')->references('id')->on('states')
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
        // For some reason the migration does not works if i drop
        // the fkey in the same Schema
        Schema::table('vids', function (Blueprint $table) {
            $table->dropForeign('vids_state_id_foreign');
        });

        Schema::table('vids', function (Blueprint $table) {
            $table->unsignedInteger('state_id')->integer()->change();

            $table->renameColumn('state_id', 'state');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->unsignedInteger('id')->integer()->change();
            
            $table->renameColumn('id', 'idstate');
        });
    }
}
