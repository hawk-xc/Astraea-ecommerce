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
        Schema::create('hampers_products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category_id');
            $table->string('subcategory_id');
            $table->string('weight')->nullable();
            $table->string('color');
            $table->string('price');
            $table->string('stock');
            $table->string('hpp');
            $table->string('margin');
            $table->string('b_layanan');
            $table->string('created_by');
            $table->string('updated_by')->nullable(true);
            $table->timestamps();
        });

        Schema::table('hampers_products', function($table) {
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hampers_products');
    }
};
