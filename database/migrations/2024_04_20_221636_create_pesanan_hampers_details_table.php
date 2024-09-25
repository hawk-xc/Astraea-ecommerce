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
        Schema::create('pesanan_hampers_details', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('order_id');
            $table->string('hampers_id');
            $table->string('quantity');
            $table->string('price');
            $table->string('sub_total_price');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_hampers_details');
    }
};
