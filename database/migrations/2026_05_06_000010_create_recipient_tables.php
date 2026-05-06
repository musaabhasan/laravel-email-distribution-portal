<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipients', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('email')->unique();
            $table->string('first_name')->nullable()->index();
            $table->string('last_name')->nullable()->index();
            $table->string('organization')->nullable()->index();
            $table->string('job_title')->nullable();
            $table->string('locale', 12)->default('en');
            $table->string('timezone')->nullable();
            $table->json('metadata')->nullable();
            $table->string('consent_source')->nullable();
            $table->timestamp('consented_at')->nullable()->index();
            $table->timestamp('unsubscribed_at')->nullable()->index();
            $table->timestamp('hard_bounced_at')->nullable()->index();
            $table->timestamp('suppressed_at')->nullable()->index();
            $table->timestamps();
            $table->index(['consented_at', 'unsubscribed_at', 'hard_bounced_at', 'suppressed_at'], 'recipients_deliverable_idx');
        });

        Schema::create('recipient_groups', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->json('criteria')->nullable();
            $table->boolean('is_dynamic')->default(false);
            $table->timestamps();
        });

        Schema::create('recipient_group_recipient', function (Blueprint $table): void {
            $table->foreignId('recipient_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['recipient_group_id', 'recipient_id'], 'recipient_group_recipient_pk');
        });

        Schema::create('suppressions', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->index();
            $table->string('reason')->index();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
            $table->unique(['email', 'reason'], 'suppressions_email_reason_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppressions');
        Schema::dropIfExists('recipient_group_recipient');
        Schema::dropIfExists('recipient_groups');
        Schema::dropIfExists('recipients');
    }
};
