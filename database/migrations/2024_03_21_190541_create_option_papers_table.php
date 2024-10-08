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
        Schema::create('option_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_question_paper")->constrained("question_papers")->cascadeOnDelete();
            $table->string("value");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('option_papers');
    }
};
