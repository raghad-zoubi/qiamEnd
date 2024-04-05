<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_exames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_exam')->nullable()->constrained('exames')->cascadeOnDelete();

            $table->foreignId("id_online_center")->nullable()->constrained("online_centers")->cascadeOnDelete();;
            $table->foreignId('id_content')->nullable()->constrained('contents')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_exames');
    }
};
