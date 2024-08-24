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
        Schema::create('question_papers', function (Blueprint $table) {
            $table->id();
          //  $table->string("select");//جواب اختيار من متعدد اختر الصح
            $table->enum("select",["خيار متعدد"
                ,"مربعات اختيار"
                ,"قائمة منسدلة"
                ,"إجابة قصيرة"
                ,"تاريخ"
                ,"وقت"]);

            $table->enum("required",["0","1"])->default("0");// اجباري او اختياري
             $table->foreignId("id_paper")->constrained("papers")->cascadeOnDelete();
             $table->string("question");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('question_papers');
    }
};
