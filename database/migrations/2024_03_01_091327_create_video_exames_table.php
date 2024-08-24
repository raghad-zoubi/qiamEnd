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
        Schema::create('video_exames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_exam')->constrained('exames')->cascadeOnDelete();
            $table->foreignId("id_video")->constrained("videos")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('course_exames');
    }
};
