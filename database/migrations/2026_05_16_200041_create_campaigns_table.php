<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('subject_ar', 255);
            $table->string('subject_en', 255)->nullable();
            $table->longText('body_ar');
            $table->longText('body_en')->nullable();
            $table->string('target_lang', 10)->nullable();
            $table->string('status', 20)->default('draft'); // draft|sending|sent|failed
            $table->unsignedInteger('sent_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
