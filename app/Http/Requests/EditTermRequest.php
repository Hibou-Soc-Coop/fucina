<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTermRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validationRules = [];
        $validationRules['term'] = ['required', 'array'];
        $validationRules['term.*'] = ['required', 'string', 'max:100'];
        $validationRules['definition'] = ['required', 'array'];
        $validationRules['definition.*'] = ['required', 'string', 'max:2000'];

        return $validationRules;
    }

    public function messages(): array
    {
        $messages = [];
        $messages['term.required'] = 'Il termine è obbligatorio.';
        $messages['term.*.required'] = 'Il termine è obbligatorio per ogni lingua.';
        $messages['term.*.string'] = 'Il termine deve essere una stringa.';
        $messages['term.*.max'] = 'Il termine non può superare i 100 caratteri.';
        $messages['definition.required'] = 'La definizione è obbligatoria.';
        $messages['definition.*.required'] = 'La definizione è obbligatoria per ogni lingua.';
        $messages['definition.*.string'] = 'La definizione deve essere una stringa.';
        $messages['definition.*.max'] = 'La definizione non può superare i 2000 caratteri.';

        return $messages;
    }
}
