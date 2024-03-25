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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_user")->unsigned()->unique()->
            constrained("users","id")->
            cascadeOnDelete()->cascadeOnUpdate();

            $table->string('name');
            $table->string('lastName')->nullable();
            $table->string('fatherName')->nullable();
            $table->enum('gender',["f","m"])->default("m");
            $table->date('birthDate')->nullable();
            $table->integer('mobilePhone')->unique();;
            $table->string('specialization')->nullable();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
