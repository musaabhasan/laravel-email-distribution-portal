<?php

namespace App\Console\Commands;

use App\Services\Email\BroadcastBatchSender;
use App\Services\Email\RateLimitWindow;
use Illuminate\Console\Command;

class SendBroadcastBatchCommand extends Command
{
    protected $signature = 'mail:send-broadcast-batch {--dry-run : Show the current allowance without sending}';

    protected $description = 'Send the next throttled batch of pending broadcast recipients.';

    public function handle(BroadcastBatchSender $sender, RateLimitWindow $window): int
    {
        if ($this->option('dry-run')) {
            $this->info('Remaining allowance: '.$window->remainingAllowance());

            return self::SUCCESS;
        }

        $sent = $sender->sendAvailable();

        $this->info("Sent {$sent} messages in this batch.");

        return self::SUCCESS;
    }
}
