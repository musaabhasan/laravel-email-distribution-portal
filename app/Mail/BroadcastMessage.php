<?php

namespace App\Mail;

use App\Models\Broadcast;
use App\Models\Recipient;
use App\Services\Email\TemplateRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Broadcast $broadcast,
        public readonly Recipient $recipient,
    ) {
    }

    public function envelope(): Envelope
    {
        $renderer = app(TemplateRenderer::class);

        return new Envelope(
            from: new Address($this->broadcast->from_email, $this->broadcast->from_name ?: config('mail.from.name')),
            replyTo: $this->broadcast->reply_to ? [new Address($this->broadcast->reply_to)] : [],
            subject: $renderer->renderSubject($this->broadcast->template, $this->recipient),
            tags: ['broadcast-'.$this->broadcast->id],
            metadata: [
                'broadcast_id' => (string) $this->broadcast->id,
                'recipient_id' => (string) $this->recipient->id,
            ],
        );
    }

    public function content(): Content
    {
        $renderer = app(TemplateRenderer::class);

        return new Content(
            view: 'mail.broadcast',
            text: 'mail.broadcast-text',
            with: [
                'html' => $renderer->renderHtml($this->broadcast->template, $this->recipient),
                'text' => $renderer->renderText($this->broadcast->template, $this->recipient),
                'broadcast' => $this->broadcast,
                'recipient' => $this->recipient,
            ],
        );
    }
}
