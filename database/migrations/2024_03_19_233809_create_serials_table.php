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
        Schema::create('serials', function (Blueprint $table) {
            $table->id();
            // المرحلة السابقة للحالية
            $table->foreignId("id_online_center")->constrained("online_centers")->cascadeOnDelete();
            $table->foreignId("id_course")->constrained("courses")->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('serials');
    }
};
