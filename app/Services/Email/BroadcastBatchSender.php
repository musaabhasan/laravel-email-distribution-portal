<?php

namespace App\Services\Email;

use App\Enums\BroadcastStatus;
use App\Enums\DeliveryStatus;
use App\Mail\BroadcastMessage;
use App\Models\BroadcastRecipient;
use App\Models\DeliveryLog;
use Illuminate\Support\Facades\Mail;
use Throwable;

class BroadcastBatchSender
{
    public function __construct(
        private readonly RateLimitWindow $rateLimit,
    ) {
    }

    public function sendAvailable(): int
    {
        $allowance = $this->rateLimit->remainingAllowance();

        if ($allowance < 1) {
            return 0;
        }

        $items = BroadcastRecipient::query()
            ->with(['broadcast.template', 'recipient'])
            ->where('status', DeliveryStatus::Pending)
            ->where(fn ($query) => $query->whereNull('available_at')->orWhere('available_at', '<=', now()))
            ->orderBy('id')
            ->limit($allowance)
            ->get();

        $sent = 0;

        foreach ($items as $item) {
            $sent += $this->sendOne($item) ? 1 : 0;
        }

        return $sent;
    }

    private function sendOne(BroadcastRecipient $item): bool
    {
        $broadcast = $item->broadcast;
        $recipient = $item->recipient;

        if (! $recipient || $recipient->suppressed_at || $recipient->unsubscribed_at || $recipient->hard_bounced_at) {
            $item->update(['status' => DeliveryStatus::Suppressed]);

            return false;
        }

        $start = microtime(true);
        $item->update([
            'status' => DeliveryStatus::Reserved,
            'reserved_at' => now(),
            'attempts' => $item->attempts + 1,
        ]);

        try {
            Mail::to($recipient->email)->send(new BroadcastMessage($broadcast, $recipient));

            $item->update([
                'status' => DeliveryStatus::Sent,
                'sent_at' => now(),
                'last_error' => null,
            ]);

            $broadcast->update(['status' => BroadcastStatus::Sending]);

            DeliveryLog::query()->create([
                'broadcast_id' => $broadcast->id,
                'recipient_id' => $recipient->id,
                'broadcast_recipient_id' => $item->id,
                'status' => 'sent',
                'smtp_code' => '250',
                'smtp_response' => 'Accepted by mail transport',
                'latency_ms' => (int) round((microtime(true) - $start) * 1000),
                'sent_at' => now(),
            ]);

            return true;
        } catch (Throwable $exception) {
            $nextStatus = $item->attempts >= config('mailflow.max_attempts')
                ? DeliveryStatus::Failed
                : DeliveryStatus::Pending;

            $item->update([
                'status' => $nextStatus,
                'available_at' => now()->addMinutes(config('mailflow.retry_minutes')),
                'last_error' => $exception->getMessage(),
            ]);

            DeliveryLog::query()->create([
                'broadcast_id' => $broadcast->id,
                'recipient_id' => $recipient->id,
                'broadcast_recipient_id' => $item->id,
                'status' => 'failed',
                'smtp_response' => $exception->getMessage(),
                'latency_ms' => (int) round((microtime(true) - $start) * 1000),
                'sent_at' => now(),
            ]);

            return false;
        }
    }
}
