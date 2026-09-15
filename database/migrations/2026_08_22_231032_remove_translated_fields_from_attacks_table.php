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
            'name',
            'arabic_name',
            'description',
            'prevention',
            'detection',
            'solution',
        ]);
         });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('attacks', function (Blueprint $table) {
        $table->string('name');
        $table->string('arabic_name');
        $table->text('description');
        $table->text('prevention');
        $table->text('detection');
        $table->text('solution');
    });
}
    
};
