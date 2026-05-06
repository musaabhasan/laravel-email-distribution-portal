<?php

namespace App\Http\Controllers;

use App\Models\Broadcast;
use App\Models\BroadcastRecipient;
use App\Models\DeliveryLog;
use App\Models\Recipient;
use App\Services\Email\RateLimitWindow;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(RateLimitWindow $window): View
    {
        return view('dashboard.index', [
            'recipientCount' => Recipient::query()->count(),
            'deliverableCount' => Recipient::query()->deliverable()->count(),
            'broadcastCount' => Broadcast::query()->count(),
            'pendingCount' => BroadcastRecipient::query()->where('status', 'pending')->count(),
            'sentToday' => DeliveryLog::query()->where('status', 'sent')->whereDate('sent_at', today())->count(),
            'remainingAllowance' => $window->remainingAllowance(),
            'recentBroadcasts' => Broadcast::query()->latest()->limit(8)->get(),
        ]);
    }
}
