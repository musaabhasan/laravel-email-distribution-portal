<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'organization' => ['nullable', 'string', 'max:180'],
            'job_title' => ['nullable', 'string', 'max:180'],
            'consent_source' => ['required', 'string', 'max:180'],
            'groups' => ['array'],
            'groups.*' => ['integer', 'exists:recipient_groups,id'],
        ];
    }
}
