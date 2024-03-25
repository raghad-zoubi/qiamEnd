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
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_user")->constrained("users")->cascadeOnDelete();
           // $table->foreignId("id_adviser")->constrained("advisers")->cascadeOnDelete();
            $table->foreignId("id_date")->constrained("dates")->cascadeOnDelete();
            $table->enum("status",["0","1"])->default("0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('adv__dat__uses');
    }
};
