<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $validationRules = [];
        $validationRules['title'] = ['required', 'array'];
        $validationRules['title.*'] = ['required', 'string', 'max:100'];
        $validationRules['subtitle'] = ['nullable', 'array'];
        $validationRules['subtitle.*'] = ['nullable', 'string', 'max:150'];
        $validationRules['description'] = ['nullable', 'array'];
        $validationRules['description.*'] = ['nullable', 'string', 'max:2000'];
        $validationRules['image'] = ['nullable', 'array'];
        $validationRules['image.id'] = ['nullable', 'integer', 'exists:media,id'];
        $validationRules['image.file.*'] = ['nullable', 'image', "mimes:jpg,jpeg,png,gif", "max:2048", "dimensions:max_width=2000,max_height=2000"];
        $validationRules['audio'] = ['nullable', 'array'];
        $validationRules['audio.id'] = ['nullable', 'integer', 'exists:media,id'];
        $validationRules['audio.file.*'] = ['nullable', 'file', "mimes:mp3,wav,ogg", "max:5120"];
        $validationRules['video'] = ['nullable', 'array'];
        $validationRules['video.id'] = ['nullable', 'integer', 'exists:media,id'];
        $validationRules['video.file.*'] = ['nullable', 'file', "mimes:mp4,mov,avi,wmv", "max:204800"];
        
        // Remove QR code validation as it's not editable

        return $validationRules;

    }

    public function messages(): array
    {
        $messages = [];
        $messages['title.required'] = 'Il titolo è obbligatorio.';
        $messages['title.*.required'] = 'Il titolo è obbligatorio per ogni lingua.';
        $messages['title.*.string'] = 'Il titolo deve essere una stringa.';
        $messages['title.*.max'] = 'Il titolo non può superare i 100 caratteri.';
        $messages['subtitle.*.string'] = 'Il sottotitolo deve essere una stringa.';
        $messages['subtitle.*.max'] = 'Il sottotitolo non può superare i 150 caratteri.';
        $messages['description.*.string'] = 'La descrizione deve essere una stringa.';
        $messages['description.*.max'] = 'La descrizione non può superare i 2000 caratteri.';
        $messages['image.file.*.image'] = 'Il file deve essere un\'immagine.';
        $messages['image.file.*.mimes'] = 'L\'immagine deve essere in formato jpg, jpeg, png o gif.';
        $messages['image.file.*.max'] = 'L\'immagine non può superare i 2MB.';
        $messages['image.file.*.dimensions'] = 'L\'immagine non può superare i 2000x2000 pixel.';
        $messages['audio.file.*.file'] = 'Il file deve essere un audio.';
        $messages['audio.file.*.mimes'] = 'L\'audio deve essere in formato mp3, wav o ogg.';
        $messages['audio.file.*.max'] = 'L\'audio non può superare i 10MB.';
        $messages['audio.file.*.uploaded'] = 'Caricamento fallito. Il file potrebbe essere più grande del limite upload_max_filesize del server php.';

        return $messages;
    }
}
