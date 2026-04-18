<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitProofRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canAccess();
    }

    public function rules(): array
    {
        return [
            'proof_type' => ['required', 'in:photo,certificate'],
            'proof_file' => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $type = $this->input('proof_type');
                    $mime = $value->getMimeType();
                    if ($type === 'photo' && ! in_array($mime, ['image/jpeg', 'image/png'])) {
                        $fail('Action photo must be a JPEG or PNG image.');
                    }
                    if ($type === 'certificate' && ! in_array($mime, ['image/jpeg', 'image/png', 'application/pdf'])) {
                        $fail('Certificate must be a JPEG, PNG, or PDF file.');
                    }
                },
            ],
        ];
    }
}
