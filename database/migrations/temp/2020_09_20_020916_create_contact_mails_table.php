<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_mails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('from_email');
            $table->string('to_email');
            $table->string('subject');
            $table->string('message');
            $table->boolean('unread')->default(1);
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
        Schema::dropIfExists('contact_mails');
    }
}
