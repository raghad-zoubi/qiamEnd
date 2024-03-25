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
        Schema::create('onlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_course")->constrained("courses")->cascadeOnDelete();
            $table->enum("Exam",["0","1"])->default("0");
            $table->enum("serial",["0","1"])->default("0");
            $table->time("durationExam");
            $table->bigInteger("numberQuestion");
            $table->bigInteger("numberHours");
            $table->bigInteger("numberContents");
            $table->bigInteger("amount");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('online_courses');
    }
};
