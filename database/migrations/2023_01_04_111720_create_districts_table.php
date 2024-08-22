<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('province_id');
            $table->string('province');
            $table->string('name');
            $table->string('type');
            $table->string('postal_code');
            $table->string('created_by');
            $table->string('updated_by')->nullable(true);
            $table->timestamps();
        });

        Schema::table('districts', function($table) {
            $table->foreign('province_id')->references('id')->on('provinces')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
};
