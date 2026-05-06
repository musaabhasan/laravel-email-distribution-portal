<?php

namespace App\Services\Email;

use App\Models\Broadcast;
use App\Models\Recipient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class RecipientSelectionService
{
    /**
     * @return Collection<int, Recipient>
     */
    public function eligibleRecipients(Broadcast $broadcast): Collection
    {
        $groupIds = $broadcast->groups()->pluck('recipient_groups.id');

        return Recipient::query()
            ->deliverable()
            ->whereHas('groups', fn (Builder $query) => $query->whereIn('recipient_groups.id', $groupIds))
            ->whereDoesntHave('queueItems', fn (Builder $query) => $query->where('broadcast_id', $broadcast->id))
            ->orderBy('id')
            ->get();
    }
}
