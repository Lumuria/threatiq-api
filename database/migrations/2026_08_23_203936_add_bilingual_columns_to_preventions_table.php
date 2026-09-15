<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preventions', function (Blueprint $table) {

            $table->string('category_ar')->nullable()->after('category');
            $table->string('category_en')->nullable()->after('category_ar');

            $table->string('importance_ar')->nullable()->after('importance');
            $table->string('importance_en')->nullable()->after('importance_ar');

            $table->string('difficulty_ar')->nullable()->after('difficulty');
            $table->string('difficulty_en')->nullable()->after('difficulty_ar');
        });
    }

    public function down(): void
    {
        Schema::table('preventions', function (Blueprint $table) {

            $table->dropColumn([
                'category_ar',
                'category_en',
                'importance_ar',
                'importance_en',
                'difficulty_ar',
                'difficulty_en',
            ]);
        });
    }
};