<?php

namespace App\Http\Controllers;

use App\Models\AuditEvent;
use App\Models\DeliverabilitySnapshot;
use App\Models\Suppression;
use Illuminate\Contracts\View\View;

class ComplianceController extends Controller
{
    public function __invoke(): View
    {
        return view('compliance.index', [
            'suppressionCount' => Suppression::query()->count(),
            'recentAuditEvents' => AuditEvent::query()->latest()->limit(30)->get(),
            'snapshots' => DeliverabilitySnapshot::query()->latest('checked_at')->limit(10)->get(),
        ]);
    }
}
