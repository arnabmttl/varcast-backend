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
        Schema::create('scorecard_contents', function (Blueprint $table) {
            $table->id();
            $table->string('banner_image')->nullable();
            $table->longText('banner_title')->nullable();
            $table->longText('banner_short_description')->nullable();
            $table->longText('banner_form_title')->nullable();
            $table->longText('banner_form_description')->nullable();
            $table->string('section_image')->nullable();
            $table->longText('section_title')->nullable();
            $table->longText('section_description')->nullable();
            $table->longText('gird_section_title')->nullable();
            $table->string('gird_image_1')->nullable();
            $table->longText('gird_title_1')->nullable();
            $table->longText('gird_short_description_1')->nullable();
            $table->string('gird_image_2')->nullable();
            $table->longText('gird_title_2')->nullable();
            $table->longText('gird_short_description_2')->nullable();
            $table->string('gird_image_3')->nullable();
            $table->longText('gird_title_3')->nullable();
            $table->longText('gird_short_description_3')->nullable();
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
        Schema::dropIfExists('scorecard_contents');
    }
};
