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
        Schema::create('categories_services', function (Blueprint $table) {
            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('services_id');

            // Clé primaire composite
            $table->primary(['categories_id', 'services_id']);

            // Clés étrangères
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('services_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories_services');
    }
};
