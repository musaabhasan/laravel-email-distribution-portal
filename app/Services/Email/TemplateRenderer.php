<?php

namespace App\Services\Email;

use App\Models\EmailTemplate;
use App\Models\Recipient;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\URL;

class TemplateRenderer
{
    public function renderSubject(EmailTemplate $template, Recipient $recipient): string
    {
        return $this->replaceVariables($template->subject, $recipient, escape: false);
    }

    public function renderHtml(EmailTemplate $template, Recipient $recipient): HtmlString
    {
        return new HtmlString($this->replaceVariables($template->html_body, $recipient, escape: true));
    }

    public function renderText(EmailTemplate $template, Recipient $recipient): string
    {
        return $this->replaceVariables($template->text_body ?: strip_tags($template->html_body), $recipient, escape: false);
    }

    private function replaceVariables(string $content, Recipient $recipient, bool $escape): string
    {
        $values = [
            'email' => $recipient->email,
            'first_name' => $recipient->first_name,
            'last_name' => $recipient->last_name,
            'full_name' => $recipient->displayName(),
            'organization' => $recipient->organization,
            'job_title' => $recipient->job_title,
            'unsubscribe_url' => $recipient->exists && $recipient->uuid
                ? URL::signedRoute('recipients.unsubscribe', $recipient)
                : '#',
        ];

        foreach (($recipient->metadata ?? []) as $key => $value) {
            if (is_scalar($value)) {
                $values["metadata.{$key}"] = (string) $value;
            }
        }

        return preg_replace_callback('/{{\s*([A-Za-z0-9_.-]+)\s*}}/', function (array $match) use ($values, $escape): string {
            $value = $values[$match[1]] ?? '';

            return $escape ? e($value) : (string) $value;
        }, $content) ?? $content;
    }
}
