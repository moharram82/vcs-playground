<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->unsignedBigInteger('requester_id');
            $table->unsignedBigInteger('invited_id');
            $table->boolean('is_request')->default(true);
            $table->dateTime('accepted_at')->nullable();
            $table->timestamps();

            $table->primary(['requester_id', 'invited_id']);

            $table->foreign('requester_id', 'fk_friends_users_requester_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('invited_id', 'fk_friends_users_invited_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
}
