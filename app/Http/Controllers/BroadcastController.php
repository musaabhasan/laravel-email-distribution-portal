<?php

namespace App\Http\Controllers;

use App\Enums\BroadcastStatus;
use App\Http\Requests\StoreBroadcastRequest;
use App\Models\Broadcast;
use App\Models\EmailTemplate;
use App\Models\RecipientGroup;
use App\Services\Email\BroadcastQueueService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function index(): View
    {
        return view('broadcasts.index', [
            'broadcasts' => Broadcast::query()->withCount('recipients')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('broadcasts.form', [
            'broadcast' => new Broadcast,
            'templates' => EmailTemplate::query()->where('is_active', true)->orderBy('name')->get(),
            'groups' => RecipientGroup::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreBroadcastRequest $request): RedirectResponse
    {
        $broadcast = Broadcast::query()->create($request->safe()->except('groups') + [
            'user_id' => $request->user()->id,
            'status' => BroadcastStatus::Scheduled,
        ]);

        $broadcast->groups()->sync($request->input('groups'));

        return redirect()->route('broadcasts.show', $broadcast)->with('status', 'Broadcast scheduled.');
    }

    public function show(Broadcast $broadcast): View
    {
        return view('broadcasts.show', [
            'broadcast' => $broadcast->load(['template', 'groups'])->loadCount('recipients'),
            'logs' => $broadcast->logs()->latest()->limit(50)->get(),
        ]);
    }

    public function approve(Request $request, Broadcast $broadcast, BroadcastQueueService $queueService): RedirectResponse
    {
        $queueService->approve($broadcast, $request->user()->id);

        return back()->with('status', 'Broadcast approved.');
    }

    public function queue(Broadcast $broadcast, BroadcastQueueService $queueService): RedirectResponse
    {
        $count = $queueService->queueRecipients($broadcast);

        return back()->with('status', "{$count} recipients queued.");
    }
}
