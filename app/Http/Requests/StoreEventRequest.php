<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'moderator']);
    }

    public function rules(): array
    {
        return [
            'term_id'     => ['nullable', 'exists:terms,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'event_date'  => ['required', 'date'],
            'location'    => ['nullable', 'string', 'max:255'],
            'status'        => ['required', 'in:draft,open,closed'],
            'cert_template' => ['nullable', 'image', 'mimes:png', 'max:5120'],
            'fb_post_url'   => ['nullable', 'url', 'max:500'],
        ];
    }
}
