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
        Schema::create('course_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_paper")->constrained("papers")->cascadeOnDelete();
            $table->foreignId("id_online_center")->constrained("online_centers")->cascadeOnDelete();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('course_papers');
    }
};
