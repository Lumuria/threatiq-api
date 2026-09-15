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
         Schema::create('attacks', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('arabic_name');
        $table->string('date');
        $table->string('type');
        $table->string('target');
        $table->text('damage');
        $table->text('description');
        $table->text('prevention');
        $table->text('detection');
        $table->text('solution');
        $table->string('severity');
        $table->string('color');
        $table->timestamps();
    });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attacks');
    }
};
