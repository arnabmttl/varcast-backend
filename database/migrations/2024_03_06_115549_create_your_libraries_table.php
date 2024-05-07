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
        Schema::create('your_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->enum('type',['playlist','podcasts','artists'])->default('playlist');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('author')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->string('about')->nullable();
            $table->enum('status',['A','I','D'])->default('A');
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
        Schema::dropIfExists('your_libraries');
    }
};
