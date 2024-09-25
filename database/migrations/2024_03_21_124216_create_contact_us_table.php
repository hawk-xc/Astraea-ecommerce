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
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('whatsapp');
            $table->string('email');
            $table->string('id_province')->nullable();
            $table->string('id_distric')->nullable();
            $table->string('id_sub_distric')->nullable();
            $table->text('address');
            $table->text('maps');
            $table->string('created_by');
            $table->string('updated_by')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};
