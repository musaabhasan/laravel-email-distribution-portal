<?php

namespace App\Services\Email;

use App\Models\Broadcast;
use Illuminate\Validation\ValidationException;

class HeaderIntegrityService
{
    public function validate(Broadcast $broadcast): void
    {
        $fromDomain = strtolower((string) str($broadcast->from_email)->after('@'));
        $allowedDomains = array_map('strtolower', config('mailflow.allowed_from_domains', []));

        if ($allowedDomains !== [] && ! in_array($fromDomain, $allowedDomains, true)) {
            throw ValidationException::withMessages([
                'from_email' => "The sender domain {$fromDomain} is not approved for outbound broadcasts.",
            ]);
        }

        if (! filter_var($broadcast->from_email, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'from_email' => 'The sender address must be a valid mailbox.',
            ]);
        }

        if ($broadcast->reply_to && ! filter_var($broadcast->reply_to, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'reply_to' => 'The reply-to address must be a valid mailbox.',
            ]);
        }
    }
}
