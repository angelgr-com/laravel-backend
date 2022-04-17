<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            // messages (id, from, message, date, party_id)
            $table->uuid('id')->primary();
            $table->uuid('from');
            $table->string('message');
            $table->date('date');
            $table->uuid('party_id');
            $table->timestamps();
            
            // If we remove a user, his or her messages
            // will be deleted
            $table->foreign('from')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            // If we update/remove a party, its related parties
            // will be deleted
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
        Schema::dropIfExists('messages');
    }
}
