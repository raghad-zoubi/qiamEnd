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
        Schema::create('user_certificate', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_certificate")->constrained("certificates")->cascadeOnDelete();
            $table->foreignId("id_booking")->constrained("booking")->cascadeOnDelete();
            $table->string("number")->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cer_pats');
    }
};
