<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBroadcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'email_template_id' => ['required', 'exists:email_templates,id'],
            'from_email' => ['required', 'email:rfc,dns', 'max:255'],
            'from_name' => ['nullable', 'string', 'max:180'],
            'reply_to' => ['nullable', 'email:rfc,dns', 'max:255'],
            'scheduled_at' => ['nullable', 'date'],
            'groups' => ['required', 'array', 'min:1'],
            'groups.*' => ['integer', 'exists:recipient_groups,id'],
        ];
    }
}
