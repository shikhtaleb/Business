<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page');
            $table->string('ip_hash', 64);
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('session_id', 64)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('created_at');
            $table->index('page');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
