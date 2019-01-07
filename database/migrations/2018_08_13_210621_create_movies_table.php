<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->integer('year')->nullable();
            $table->date('released')->nullable();
            $table->integer('runtime')->nullable();
            $table->text('plot')->nullable();
            $table->text('review')->nullable();
            $table->string('poster', 500)->nullable();
            $table->string('internet_movie_database_rating')->nullable();
            $table->string('rotten_tomatoes_rating')->nullable();
            $table->string('metacritic_rating')->nullable();
            $table->string('imdb_rating')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();

            $table->unique(['title', 'year']);

            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
