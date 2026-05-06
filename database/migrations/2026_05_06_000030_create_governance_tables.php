<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event')->index();
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['auditable_type', 'auditable_id']);
        });

        Schema::create('deliverability_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->string('domain')->index();
            $table->boolean('spf_pass')->default(false);
            $table->boolean('dkim_pass')->default(false);
            $table->boolean('dmarc_pass')->default(false);
            $table->unsignedTinyInteger('score')->default(0)->index();
            $table->json('findings')->nullable();
            $table->timestamp('checked_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliverability_snapshots');
        Schema::dropIfExists('audit_events');
    }
};
