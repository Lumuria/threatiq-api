<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prevention_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prevention_id')
                ->constrained('preventions')
                ->onDelete('cascade');

            $table->string('language', 2);

            $table->string('title');
            $table->text('description');
            $table->json('tips');

            $table->timestamps();

            $table->unique(['prevention_id', 'language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prevention_translations');
    }
};