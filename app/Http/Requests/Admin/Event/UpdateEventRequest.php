<?php

namespace App\Http\Requests\Admin\Event; // 'app'을 'App'으로 변경

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'url' => 'required|url|max:255',
            'thumbnail_url' => 'nullable|url|max:255',
            'event_source_id' => 'nullable|exists:event_sources,id', // 추가
        ];
    }
}