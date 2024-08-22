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
        Schema::create('sub_districts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('district_id');
            $table->string('name');
            $table->string('created_by');
            $table->string('updated_by')->nullable(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_districts');
    }
};
