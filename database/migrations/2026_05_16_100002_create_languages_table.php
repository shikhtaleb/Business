<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();   // ar, en, fr, etc.
            $table->string('name', 100);             // English, العربية
            $table->string('native_name', 100);      // Name in that language
            $table->string('flag_code', 10)->nullable(); // country code for display (SA, GB)
            $table->boolean('is_rtl')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->json('translations')->nullable(); // the full i18n JSON
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
