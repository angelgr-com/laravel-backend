<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            // parties (id, name, game_id, user_id)
            // user_id here means party owner or creator
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('game_id');
            $table->uuid('user_id');
            $table->timestamps();
            
            // If we update/remove a game, related parties
            // will be updated/deleted
            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            // If we remove a user, as a creator/owner,
            // his or her related parties will be deleted
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parties');
    }
}
