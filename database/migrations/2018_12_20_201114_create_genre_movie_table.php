<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenreMovieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genre_movie', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('genre_id');
            $table->unsignedInteger('movie_id');
            $table->timestamps();

            $table->foreign('genre_id')
                ->references('id')->on('genres')
                ->onDelete('cascade');

            $table->foreign('movie_id')
                ->references('id')->on('movies')
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
        Schema::dropIfExists('genre_movie');
    }
}
