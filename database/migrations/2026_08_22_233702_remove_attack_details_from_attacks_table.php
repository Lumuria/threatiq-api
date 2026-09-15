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
        Schema::table('attacks', function (Blueprint $table) {
            $table->dropColumn([
                'date',
                'type',
                'target',
                'damage',
                'severity',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attacks', function (Blueprint $table) {
            $table->string('date');
            $table->string('type');
            $table->string('target');
            $table->string('damage');
            $table->string('severity');
        });
    }
};