<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Recipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'email',
        'first_name',
        'last_name',
        'organization',
        'job_title',
        'locale',
        'timezone',
        'metadata',
        'consent_source',
        'consented_at',
        'unsubscribed_at',
        'hard_bounced_at',
        'suppressed_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'consented_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
            'hard_bounced_at' => 'datetime',
            'suppressed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Recipient $recipient): void {
            $recipient->uuid ??= (string) Str::uuid();
            $recipient->email = Str::lower(trim($recipient->email));
        });
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(RecipientGroup::class, 'recipient_group_recipient')->withTimestamps();
    }

    public function queueItems(): HasMany
    {
        return $this->hasMany(BroadcastRecipient::class);
    }

    public function scopeDeliverable(Builder $query): Builder
    {
        return $query
            ->whereNotNull('consented_at')
            ->whereNull('unsubscribed_at')
            ->whereNull('hard_bounced_at')
            ->whereNull('suppressed_at');
    }

    public function displayName(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: $this->email;
    }
}
