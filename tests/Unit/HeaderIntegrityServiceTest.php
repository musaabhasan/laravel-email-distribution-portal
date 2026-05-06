<?php

namespace Tests\Unit;

use App\Models\Broadcast;
use App\Services\Email\HeaderIntegrityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class HeaderIntegrityServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_blocks_unapproved_sender_domains(): void
    {
        config(['mailflow.allowed_from_domains' => ['example.org']]);

        $broadcast = new Broadcast([
            'from_email' => 'sender@unapproved.test',
        ]);

        $this->expectException(ValidationException::class);

        app(HeaderIntegrityService::class)->validate($broadcast);
    }

    public function test_it_accepts_approved_sender_domains(): void
    {
        config(['mailflow.allowed_from_domains' => ['example.org']]);

        $broadcast = new Broadcast([
            'from_email' => 'sender@example.org',
        ]);

        app(HeaderIntegrityService::class)->validate($broadcast);

        $this->assertTrue(true);
    }
}
