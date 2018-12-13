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
            $table->integer('year');
            $table->date('released');
            $table->string('runtime');
            $table->text('plot');
            $table->text('review');
            $table->string('poster');
            $table->decimal('internet_movie_database_rating');
            $table->integer('rotten_tomatoes_rating');
            $table->integer('metacritic_rating');
            $table->decimal('imdb_rating');
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
