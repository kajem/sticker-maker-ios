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
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('type', 50);
            $table->string('slug')->unique();
            $table->string('author', 100)->nullable();
            $table->string('tag')->nullable();
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('banner')->nullable();
            $table->string('banner_alt')->nullable();
            $table->text('short_description')->nullable();
            $table->mediumText('description')->nullable();
            $table->dateTime('published_date')->nullable();
            $table->text('related_posts')->nullable();
            $table->string('status', 10);
            $table->tinyInteger('created_by');
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
        Schema::dropIfExists('posts');
    }
}
