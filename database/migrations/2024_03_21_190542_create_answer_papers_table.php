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
        Schema::create('answer_papers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_user")->constrained("users")->cascadeOnDelete();
            $table->text("answer")->nullable();
            $table->foreignId("id_question_paper")->constrained("question_papers")->cascadeOnDelete();
            $table->foreignId("id_option_paper")->nullable()->constrained("option_papers")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('answer_papers');
    }
};
