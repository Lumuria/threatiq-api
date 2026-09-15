<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->string('type_ar');
            $table->string('type_en');

            $table->string('severity_ar');
            $table->string('severity_en');

            $table->string('status_ar');
            $table->string('status_en');

            $table->text('description_ar');
            $table->text('description_en');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};