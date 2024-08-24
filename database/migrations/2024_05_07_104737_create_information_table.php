<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->String("director")->nullable();
            $table->text("site")->nullable();
            $table->text("time")->nullable();
            $table->text("email")->nullable();
            $table->text("nubmer")->nullable();
            $table->text("facebook")->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('information');
    }
};
