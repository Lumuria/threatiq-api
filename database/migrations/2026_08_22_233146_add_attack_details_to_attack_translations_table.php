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
        Schema::table('attack_translations', function (Blueprint $table) {
            $table->string('date')->after('title');
            $table->string('type')->after('date');
            $table->string('target')->after('type');
            $table->string('damage')->after('target');
            $table->string('severity')->after('damage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attack_translations', function (Blueprint $table) {
            $table->dropColumn([
                'date',
                'type',
                'target',
                'damage',
                'severity',
            ]);
        });
    }
};