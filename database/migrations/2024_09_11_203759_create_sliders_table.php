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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->enum('view', ['desktop', 'smartphone'])->default('desktop');
            $table->string('button_title')->nullable();
            $table->string('button_link')->nullable();
            $table->string('button_background')->default('#ffffff');
            $table->string('button_text_color')->default('#000000');
            $table->string('button_horizontal_layout')->default('right');
            $table->string('button_vertical_layout')->default('bottom');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
