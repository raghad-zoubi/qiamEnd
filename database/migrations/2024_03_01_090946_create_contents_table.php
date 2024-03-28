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
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_online_center")->constrained("online_centers")->cascadeOnDelete();
            $table->string("numberHours");
            $table->string("numberVideos");
            $table->string('name');
            $table->text("photo");
            $table->integer("rank");
            $table->enum("exam",["0","1"])->default("0");//p
            $table->time("durationExam");//p
            $table->bigInteger("numberQuestion");//p

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
};
