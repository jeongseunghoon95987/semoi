<?php

namespace app\Http\Requests\Admin\EventSource;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventSourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // 관리자만 접근 가능하므로 true로 설정
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'list_selector' => 'required|string|max:255',
            'item_selector' => 'required|string|max:255',
            'title_selector' => 'required|string|max:255',
            'date_selector' => 'nullable|string|max:255',
            'start_date_selector' => 'nullable|string|max:255',
            'end_date_selector' => 'nullable|string|max:255',
            'url_selector' => 'required|string|max:255',
            'description_selector' => 'nullable|string|max:255',
            'thumbnail_selector' => 'nullable|string|max:255',
        ];
    }
}
