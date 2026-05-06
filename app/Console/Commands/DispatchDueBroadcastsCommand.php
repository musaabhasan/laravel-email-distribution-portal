<?php

namespace App\Console\Commands;

use App\Enums\BroadcastStatus;
use App\Models\Broadcast;
use App\Services\Email\BroadcastQueueService;
use Illuminate\Console\Command;

class DispatchDueBroadcastsCommand extends Command
{
    protected $signature = 'mail:dispatch-due-broadcasts';

    protected $description = 'Queue approved broadcasts whose scheduled time has arrived.';

    public function handle(BroadcastQueueService $queueService): int
    {
        $broadcasts = Broadcast::query()
            ->where('status', BroadcastStatus::Approved)
            ->where(fn ($query) => $query->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now()))
            ->get();

        $queued = 0;

        foreach ($broadcasts as $broadcast) {
            $queued += $queueService->queueRecipients($broadcast);
        }

        $this->info("Queued {$queued} recipient records.");

        return self::SUCCESS;
    }
}
