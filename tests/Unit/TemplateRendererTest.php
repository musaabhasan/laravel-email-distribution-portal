<?php

namespace Tests\Unit;

use App\Models\EmailTemplate;
use App\Models\Recipient;
use App\Services\Email\TemplateRenderer;
use Tests\TestCase;

class TemplateRendererTest extends TestCase
{
    public function test_it_renders_recipient_variables(): void
    {
        $template = new EmailTemplate([
            'subject' => 'Hello {{ first_name }}',
            'html_body' => '<p>{{ full_name }} from {{ organization }}</p>',
            'text_body' => '{{ full_name }}',
        ]);

        $recipient = new Recipient([
            'email' => 'person@example.org',
            'first_name' => 'Maya',
            'last_name' => 'Hassan',
            'organization' => 'Example College',
        ]);

        $renderer = app(TemplateRenderer::class);

        $this->assertSame('Hello Maya', $renderer->renderSubject($template, $recipient));
        $this->assertStringContainsString('Maya Hassan', (string) $renderer->renderHtml($template, $recipient));
        $this->assertSame('Maya Hassan', $renderer->renderText($template, $recipient));
    }
}
