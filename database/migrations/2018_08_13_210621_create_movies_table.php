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
            $table->text('title');
            $table->integer('year')->nullable();
            $table->date('released')->nullable();
            $table->integer('runtime')->nullable();
            $table->text('plot')->nullable();
            $table->text('review')->nullable();
            $table->string('poster', 500)->nullable();
            $table->decimal('internet_movie_database_rating')->nullable();
            $table->integer('rotten_tomatoes_rating')->nullable();
            $table->integer('metacritic_rating')->nullable();
            $table->decimal('imdb_rating')->nullable();
            $table->text('slug');
            $table->timestamps();
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
