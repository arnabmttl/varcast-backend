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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->nullable();
            $table->string('title')->nullable();
            $table->bigInteger('qty')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('budget',12,2)->nullable();
            $table->longText('address')->nullable();
            $table->longText('details')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->enum('status',['N','I','Com','C','IP'])->default('N');
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
        Schema::dropIfExists('leads');
    }
};
