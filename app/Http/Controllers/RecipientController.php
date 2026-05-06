<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipientRequest;
use App\Models\Recipient;
use App\Models\RecipientGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function index(): View
    {
        return view('recipients.index', [
            'recipients' => Recipient::query()->latest()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return view('recipients.form', [
            'recipient' => new Recipient,
            'groups' => RecipientGroup::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreRecipientRequest $request): RedirectResponse
    {
        $recipient = Recipient::query()->updateOrCreate(
            ['email' => strtolower($request->string('email'))],
            $request->safe()->except('groups') + ['consented_at' => now()]
        );

        $recipient->groups()->sync($request->input('groups', []));

        return redirect()->route('recipients.index')->with('status', 'Recipient saved.');
    }

    public function show(Recipient $recipient): View
    {
        return view('recipients.show', ['recipient' => $recipient->load('groups')]);
    }

    public function unsubscribe(Request $request, Recipient $recipient): View
    {
        $recipient->update(['unsubscribed_at' => now()]);

        return view('recipients.unsubscribed', ['recipient' => $recipient]);
    }
}
