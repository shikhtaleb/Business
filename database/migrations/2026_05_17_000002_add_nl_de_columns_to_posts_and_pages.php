<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->text('body_nl')->nullable()->after('body_en');
            $table->text('body_de')->nullable()->after('body_nl');
            $table->text('excerpt_nl')->nullable()->after('excerpt_en');
            $table->text('excerpt_de')->nullable()->after('excerpt_nl');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->longText('body_nl')->nullable()->after('body_en');
            $table->longText('body_de')->nullable()->after('body_nl');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['body_nl', 'body_de', 'excerpt_nl', 'excerpt_de']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['body_nl', 'body_de']);
        });
    }
};
