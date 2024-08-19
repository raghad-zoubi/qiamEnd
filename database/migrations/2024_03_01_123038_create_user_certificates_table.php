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
            $table->text("certificate")->nullable();//constrained("certificates")->cascadeOnDelete();
            $table->foreignId("id_online_center")->constrained("online_centers")->cascadeOnDelete();
            $table->foreignId("id_user")->constrained("users")->cascadeOnDelete();
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
