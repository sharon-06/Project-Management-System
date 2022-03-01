<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_has_teams', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('user_has_teams');
    }
}
