<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class PreferenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'source_ids' => ['sometimes', 'array', 'min:1'],
            'source_ids.*' => [
                'integer',
                'exists:sources,id',
            ],
            'category_ids' => ['sometimes', 'array', 'min:1'],
            'category_ids.*' => [
                'integer',
                'exists:categories,id',
            ],
            'author_ids' => ['sometimes', 'array', 'min:1'],
            'author_ids.*' => [
                'integer',
                'exists:authors,id',
            ],
        ];
    }
}
