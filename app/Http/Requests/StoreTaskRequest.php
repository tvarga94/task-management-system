<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'length' => 'nullable|integer|min:0',
            'done' => 'nullable|boolean',
            'assignees' => 'nullable|array|max:4',
            'assignees.*' => 'string',
            'priority' => 'nullable|in:low,normal,high',
            'scheduled_day' => ['required', 'date', function ($attribute, $value, $fail) {
                $dayOfWeek = \Carbon\Carbon::parse($value)->dayOfWeek;
                if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                    $fail('error');
                }
            }],
        ];
    }
}
