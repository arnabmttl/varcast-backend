<?php

use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
use Jenssegers\Mongodb\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'mongodb';
    public function up()
    {
        

        Schema::create('lives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->longText('description')->nullable();
            $table->longText('imageUrl')->nullable();
            $table->longText('videoUrl')->nullable();
            $table->boolean('isActive')->nullable()->default(true);
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
        Schema::dropIfExists('lives');
    }
};
