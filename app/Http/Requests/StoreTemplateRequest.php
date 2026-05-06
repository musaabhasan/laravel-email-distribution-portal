<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'subject' => ['required', 'string', 'max:220'],
            'html_body' => ['required', 'string'],
            'text_body' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
