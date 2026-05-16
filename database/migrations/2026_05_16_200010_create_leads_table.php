<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 255);
            $table->string('phone', 50)->nullable();
            $table->string('source', 50)->default('contact_form'); // contact_form|manual|import|api
            $table->string('status', 30)->default('new');          // new|contacted|qualified|lost|converted
            $table->string('priority', 20)->default('normal');     // low|normal|high|urgent
            $table->text('notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('message_id')->nullable()->constrained('messages')->nullOnDelete();
            $table->string('lang', 10)->default('ar');
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('lead_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('note');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_notes');
        Schema::dropIfExists('leads');
    }
};
