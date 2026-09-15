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
        Schema::create('attack_translations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('attack_id')
              ->constrained('attacks')
              ->onDelete('cascade');

        $table->string('language', 2);

        $table->string('name');
        $table->text('description');
        $table->text('prevention');
        $table->text('detection');
        $table->text('solution');

        $table->timestamps();

        $table->unique(['attack_id', 'language']);
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attack_translations');
    }
};
