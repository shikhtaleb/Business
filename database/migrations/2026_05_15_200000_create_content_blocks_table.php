<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('key');
            $table->string('lang', 10)->default('ar');
            $table->text('value')->nullable();
            $table->string('type', 20)->default('text'); // text|html|image_url
            $table->timestamps();

            $table->unique(['section', 'key', 'lang']);
            $table->index(['section', 'lang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
    }
};
