<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'broadcast_id',
        'recipient_id',
        'status',
        'attempts',
        'reserved_at',
        'available_at',
        'sent_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'status' => DeliveryStatus::class,
            'reserved_at' => 'datetime',
            'available_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function broadcast(): BelongsTo
    {
        return $this->belongsTo(Broadcast::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }
}
