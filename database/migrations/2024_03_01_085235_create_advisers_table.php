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
        Schema::create('advisers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_user")->nullable()->constrained("users")
                ->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->enum('type',["قانونية","أسرية","نفسية"])->default("نفسية");
            $table->string('photo')->nullable();
            $table->text('about')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('advisers');
    }
};
