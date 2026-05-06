<?php

namespace App\Services\Email;

use App\Models\DeliveryLog;
use Carbon\CarbonImmutable;

class RateLimitWindow
{
    public function limit(): int
    {
        return max(1, (int) config('mailflow.batch_limit', 400));
    }

    public function windowMinutes(): int
    {
        return max(1, (int) config('mailflow.window_minutes', 60));
    }

    public function sentWithinWindow(): int
    {
        return DeliveryLog::query()
            ->where('status', 'sent')
            ->where('sent_at', '>=', CarbonImmutable::now()->subMinutes($this->windowMinutes()))
            ->count();
    }

    public function remainingAllowance(): int
    {
        return max(0, $this->limit() - $this->sentWithinWindow());
    }
}
