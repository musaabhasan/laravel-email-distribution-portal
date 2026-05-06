<?php

namespace Tests\Feature;

use App\Enums\BroadcastStatus;
use App\Models\Broadcast;
use App\Models\EmailTemplate;
use App\Models\Recipient;
use App\Models\RecipientGroup;
use App\Models\User;
use App\Services\Email\BroadcastQueueService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BroadcastQueueServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_queues_only_deliverable_group_recipients(): void
    {
        config(['mailflow.allowed_from_domains' => ['example.org']]);

        $user = User::factory()->create();
        $group = RecipientGroup::query()->create(['name' => 'Faculty']);
        $deliverable = Recipient::factory()->create();
        $blocked = Recipient::factory()->create(['unsubscribed_at' => now()]);
        $group->recipients()->attach([$deliverable->id, $blocked->id]);
        $template = EmailTemplate::query()->create([
            'user_id' => $user->id,
            'name' => 'Notice',
            'subject' => 'Hello',
            'html_body' => '<p>Hello</p>',
            'is_active' => true,
        ]);
        $broadcast = Broadcast::query()->create([
            'user_id' => $user->id,
            'email_template_id' => $template->id,
            'name' => 'Notice',
            'from_email' => 'sender@example.org',
            'status' => BroadcastStatus::Approved,
        ]);
        $broadcast->groups()->attach($group);

        $count = app(BroadcastQueueService::class)->queueRecipients($broadcast);

        $this->assertSame(1, $count);
        $this->assertDatabaseHas('broadcast_recipients', [
            'broadcast_id' => $broadcast->id,
            'recipient_id' => $deliverable->id,
        ]);
    }
}
