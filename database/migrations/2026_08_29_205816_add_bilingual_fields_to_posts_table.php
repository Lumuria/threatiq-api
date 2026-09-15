<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('title');
            $table->text('body_ar')->nullable()->after('title_ar');

            $table->string('title_en')->nullable()->after('body');
            $table->text('body_en')->nullable()->after('title_en');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'title_ar',
                'body_ar',
                'title_en',
                'body_en',
            ]);
        });
    }
};