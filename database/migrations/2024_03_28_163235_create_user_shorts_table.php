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
        Schema::create('user_shorts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->references('_id')->on('users')->onDelete('cascade');
            $table->json('taging')->nullable();
            $table->json('category')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('video')->nullable();
            $table->enum('status',['A','I','D','DR'])->default('A');
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
        Schema::dropIfExists('user_shorts');
    }
};
