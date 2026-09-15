<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preventions', function (Blueprint $table) {
            foreach (['category', 'importance', 'difficulty'] as $column) {
                if (Schema::hasColumn('preventions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('preventions', function (Blueprint $table) {
            if (!Schema::hasColumn('preventions', 'category')) {
                $table->string('category')->nullable();
            }

            if (!Schema::hasColumn('preventions', 'importance')) {
                $table->string('importance')->nullable();
            }

            if (!Schema::hasColumn('preventions', 'difficulty')) {
                $table->string('difficulty')->nullable();
            }
        });
    }
};