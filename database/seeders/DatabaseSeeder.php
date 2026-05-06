<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\Recipient;
use App\Models\RecipientGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.org'],
            [
                'name' => 'Portal Administrator',
                'password' => Hash::make('ChangeMe-Immediately-2026'),
                'role' => 'administrator',
                'mfa_enabled' => false,
            ]
        );

        $group = RecipientGroup::query()->firstOrCreate(
            ['name' => 'Professional Development'],
            ['description' => 'Example consent-based distribution segment.']
        );

        $recipient = Recipient::query()->firstOrCreate(
            ['email' => 'recipient@example.org'],
            [
                'first_name' => 'Sample',
                'last_name' => 'Recipient',
                'organization' => 'Example Organization',
                'consent_source' => 'Seed data',
                'consented_at' => now(),
            ]
        );

        $recipient->groups()->syncWithoutDetaching([$group->id]);

        EmailTemplate::query()->firstOrCreate(
            ['name' => 'Program Announcement'],
            [
                'user_id' => $admin->id,
                'subject' => 'Professional development update for {{ first_name }}',
                'html_body' => '<p>Dear {{ full_name }},</p><p>We are pleased to share a professional development update for {{ organization }}.</p><p><a href="{{ unsubscribe_url }}">Unsubscribe</a></p>',
                'text_body' => 'Dear {{ full_name }}, professional development update for {{ organization }}. Unsubscribe: {{ unsubscribe_url }}',
                'variables' => ['first_name', 'full_name', 'organization', 'unsubscribe_url'],
                'is_active' => true,
            ]
        );
    }
}
