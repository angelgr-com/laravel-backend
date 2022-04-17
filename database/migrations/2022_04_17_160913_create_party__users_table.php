<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party__users', function (Blueprint $table) {
            // parties_users (id, user_id, party_id)
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('party_id');
            $table->timestamps();
            
            // If we remove a user, he or she will
            // removed from the party
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            // If we remove a party, all users from
            // the party will be deleted
            $table->foreign('party_id')
                  ->references('id')
                  ->on('parties')
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
        Schema::dropIfExists('party__users');
    }
}
