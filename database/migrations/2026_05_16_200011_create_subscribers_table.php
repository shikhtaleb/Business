<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name', 150)->nullable();
            $table->string('lang', 10)->default('ar');
            $table->string('status', 20)->default('active'); // active|unsubscribed|bounced
            $table->string('source', 50)->default('website'); // website|import|manual
            $table->string('token', 64)->unique();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('subject_ar');
            $table->string('subject_en')->nullable();
            $table->longText('body_ar');
            $table->longText('body_en')->nullable();
            $table->string('status', 20)->default('draft'); // draft|sending|sent|failed
            $table->string('target_lang', 10)->nullable();  // null = all languages
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('open_count')->default(0);
            $table->timestamps();
        });

        Schema::create('campaign_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->cascadeOnDelete();
            $table->foreignId('subscriber_id')->constrained('subscribers')->cascadeOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->unique(['campaign_id', 'subscriber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_subscribers');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('subscribers');
    }
};
