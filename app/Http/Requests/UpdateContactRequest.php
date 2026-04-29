<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contact = $this->route('contact');

        return [
            'name' => ['required', 'string', 'min:6'],
            'contact' => [
                'required',
                'digits:9',
                Rule::unique('contacts', 'contact')->ignore($contact),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('contacts', 'email')->ignore($contact),
            ],
        ];
    }
}
