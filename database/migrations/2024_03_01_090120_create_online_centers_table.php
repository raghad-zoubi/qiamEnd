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
        Schema::create('online_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_online")->nullable()->constrained("onlines")->cascadeOnDelete();
            $table->foreignId("id_center")->nullable()->constrained("centers")->cascadeOnDelete();
            $table->foreignId("id_course")->constrained("courses")->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('online_centers');
    }
};
