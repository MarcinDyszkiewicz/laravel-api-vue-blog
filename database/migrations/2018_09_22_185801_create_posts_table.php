<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(/**
         * @param Blueprint $table
         */
            'posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('movie_id')->nullable();
            $table->string('slug', 255)->unique();
            $table->text('title');
            $table->longText('body');
            $table->string('image');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('summary')->nullable();
            $table->text('short_description')->nullable();
            $table->boolean('is_published')->default(false);
            $table->date('published_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('movie_id')
                ->references('id')->on('movies')
                ->onDelete('cascade');

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
        Schema::dropIfExists('posts');
    }
}
