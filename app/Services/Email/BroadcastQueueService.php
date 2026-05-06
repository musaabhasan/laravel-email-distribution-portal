<?php

namespace App\Services\Email;

use App\Enums\BroadcastStatus;
use App\Enums\DeliveryStatus;
use App\Models\Broadcast;
use Illuminate\Support\Facades\DB;

class BroadcastQueueService
{
    public function __construct(
        private readonly HeaderIntegrityService $headers,
        private readonly RecipientSelectionService $recipients,
    ) {
    }

    public function approve(Broadcast $broadcast, int $userId): Broadcast
    {
        $this->headers->validate($broadcast);

        $broadcast->forceFill([
            'status' => BroadcastStatus::Approved,
            'approved_by' => $userId,
            'approved_at' => now(),
        ])->save();

        return $broadcast;
    }

    public function queueRecipients(Broadcast $broadcast): int
    {
        $this->headers->validate($broadcast);

        return DB::transaction(function () use ($broadcast): int {
            $count = 0;

            foreach ($this->recipients->eligibleRecipients($broadcast) as $recipient) {
                $broadcast->recipients()->create([
                    'recipient_id' => $recipient->id,
                    'status' => DeliveryStatus::Pending,
                    'available_at' => now(),
                ]);
                $count++;
            }

            $broadcast->forceFill([
                'status' => BroadcastStatus::Queued,
                'queued_at' => now(),
            ])->save();

            return $count;
        });
    }
}
