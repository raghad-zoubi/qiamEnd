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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_video")->constrained("videos")->cascadeOnDelete();
            $table->foreignId("id_booking")->constrained("booking")->cascadeOnDelete();
            $table->time("endTime");
            $table->enum("done",["0","1"])->default("0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tracks');
    }
};
