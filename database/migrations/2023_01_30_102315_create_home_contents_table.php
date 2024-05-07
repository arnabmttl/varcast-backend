<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_contents', function (Blueprint $table) {
            $table->id();
            $table->longText('title_1')->nullable();
            $table->longText('short_title_1')->nullable();
            $table->string('image_1')->nullable();
            $table->longText('image_title_1')->nullable();
            $table->string('image_2')->nullable();
            $table->longText('image_title_2')->nullable();
            $table->string('image_3')->nullable();
            $table->longText('image_title_3')->nullable();
            $table->string('image_4')->nullable();
            $table->longText('image_title_4')->nullable();
            $table->longText('title_2')->nullable();
            $table->longText('short_title_2')->nullable();
            $table->string('image_5')->nullable();
            $table->longText('title_3')->nullable();
            $table->longText('title_4')->nullable();
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
        Schema::dropIfExists('home_contents');
    }
};
