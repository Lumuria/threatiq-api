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
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // Title
            $table->json('title');

            // Summary
            $table->json('summary');

            // Full content
            $table->json('content');

            // Category
            $table->json('category');

            // Severity
            $table->json('severity');

            // Source
            $table->json('source');

            // Published date
            $table->date('date')->nullable();

            // Recommendations
            $table->json('recommendations')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};