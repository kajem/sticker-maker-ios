<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('tag')->nullable();
            $table->string('code');
            $table->integer('category_id');
            $table->string('author');
            $table->string('thumb');
            $table->text('stickers');
            $table->smallInteger('total_sticker');
            $table->boolean('is_premium')->default(1);
            $table->boolean('is_animate')->default(0);
            $table->string('telegram_name')->nullable();
            $table->integer('sort');
            $table->boolean('status');
            $table->integer('created_by');
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
        Schema::dropIfExists('items');
    }
}
