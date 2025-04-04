<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('assignees') && is_string($this->assignees)) {
            $this->merge([
                'assignees' => array_filter(
                    array_map('trim', explode(',', $this->assignees))
                ),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'length' => ['nullable', 'integer', 'min:0'],
            'done' => ['nullable', 'boolean'],
            'assignees' => ['nullable', 'array', 'max:4'],
            'assignees.*' => ['string'],
            'priority' => ['nullable', Rule::in(['low', 'normal', 'high'])],
            'scheduled_day' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $dayOfWeek = Carbon::parse($value)->dayOfWeek;
                    if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                        $fail('The scheduled day must be a weekday (Monday to Friday).');
                    }
                },
            ],
        ];
    }
}
