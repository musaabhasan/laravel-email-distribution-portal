<?php

namespace App\Http\Controllers;

use App\Models\RecipientGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecipientGroupController extends Controller
{
    public function index(): View
    {
        return view('recipients.groups', [
            'groups' => RecipientGroup::query()->withCount('recipients')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('recipients.group-form', ['group' => new RecipientGroup]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:180', 'unique:recipient_groups,name'],
            'description' => ['nullable', 'string'],
        ]);

        RecipientGroup::query()->create($validated);

        return redirect()->route('groups.index')->with('status', 'Group created.');
    }

    public function show(RecipientGroup $group): View
    {
        return view('recipients.group-show', [
            'group' => $group->loadCount('recipients'),
            'recipients' => $group->recipients()->paginate(30),
        ]);
    }
}
