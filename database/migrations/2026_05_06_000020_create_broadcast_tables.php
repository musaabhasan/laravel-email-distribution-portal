<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('name')->unique();
            $table->string('subject');
            $table->longText('html_body');
            $table->longText('text_body')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('broadcasts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('email_template_id')->constrained()->restrictOnDelete();
            $table->string('name')->index();
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->string('reply_to')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('scheduled_at')->nullable()->index();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['status', 'scheduled_at'], 'broadcasts_status_schedule_idx');
        });

        Schema::create('broadcast_recipient_group', function (Blueprint $table): void {
            $table->foreignId('broadcast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_group_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['broadcast_id', 'recipient_group_id'], 'broadcast_recipient_group_pk');
        });

        Schema::create('broadcast_recipients', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('broadcast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('reserved_at')->nullable()->index();
            $table->timestamp('available_at')->nullable()->index();
            $table->timestamp('sent_at')->nullable()->index();
            $table->text('last_error')->nullable();
            $table->timestamps();
            $table->unique(['broadcast_id', 'recipient_id'], 'broadcast_recipient_unique');
            $table->index(['status', 'available_at', 'attempts'], 'broadcast_recipients_send_idx');
        });

        Schema::create('delivery_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('broadcast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broadcast_recipient_id')->nullable()->constrained()->nullOnDelete();
            $table->string('message_id')->nullable()->index();
            $table->string('status')->index();
            $table->string('smtp_code', 16)->nullable();
            $table->text('smtp_response')->nullable();
            $table->unsignedInteger('latency_ms')->nullable();
            $table->timestamp('sent_at')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['broadcast_id', 'status', 'sent_at'], 'delivery_logs_broadcast_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_logs');
        Schema::dropIfExists('broadcast_recipients');
        Schema::dropIfExists('broadcast_recipient_group');
        Schema::dropIfExists('broadcasts');
        Schema::dropIfExists('email_templates');
    }
};
