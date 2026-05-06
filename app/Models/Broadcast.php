<?php

namespace App\Models;

use App\Enums\BroadcastStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_template_id',
        'name',
        'from_email',
        'from_name',
        'reply_to',
        'status',
        'scheduled_at',
        'approved_at',
        'approved_by',
        'queued_at',
        'completed_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'status' => BroadcastStatus::class,
            'scheduled_at' => 'datetime',
            'approved_at' => 'datetime',
            'queued_at' => 'datetime',
            'completed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'email_template_id');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(RecipientGroup::class)->withTimestamps();
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(BroadcastRecipient::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(DeliveryLog::class);
    }
}
