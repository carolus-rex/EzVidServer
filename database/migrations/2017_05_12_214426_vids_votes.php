<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VidsVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vids_votes', function(Blueprint $table) {
            $table->engine = "InnoDB";

            $table->increments('id');
            $table->unsignedInteger('vid_id');
            $table->unsignedInteger('user_id');
            $table->boolean('should_keep');

            $table->foreign('vid_id')->references('id')->on('vids')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // lets cascade for now
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vids_votes');
    }
}
