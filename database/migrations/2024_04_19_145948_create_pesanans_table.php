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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->date('order_date');
            $table->string('costumer_id')->nullable();
            $table->string('code_discount')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('status');
            $table->string('no_nota');
            $table->string('address')->nullable();
            $table->string('shipping')->nullable();
            $table->string('shipping_status')->nullable();
            $table->string('app_admin')->nullable();
            $table->string('sub_total_price');
            $table->string('total_price')->nullable();
            $table->string('payment_link')->nullable();
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
        Schema::dropIfExists('pesanans');
    }
};
