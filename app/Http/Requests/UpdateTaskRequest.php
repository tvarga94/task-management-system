<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Task;
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
                    array_map(
                        fn($a) => strtolower(trim($a)),
                        explode(',', $this->assignees)
                    )
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
            'assignees' => [
                'nullable',
                'array',
                'max:4',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        return;
                    }

                    $scheduledDay = $this->input('scheduled_day');
                    $taskId = $this->route('task')->id ?? null;

                    if (!$scheduledDay) {
                        return;
                    }

                    foreach ($value as $assignee) {
                        $totalMinutes = Task::whereJsonContains('assignees', $assignee)
                            ->where('scheduled_day', $scheduledDay)
                            ->when($taskId, fn($q) => $q->where('id', '!=', $taskId))
                            ->sum('length');

                        $newLength = (int) $this->input('length', 0);

                        if (($totalMinutes + $newLength) > 480) {
                            $fail("The assignee '$assignee' would exceed 8 hours of work on $scheduledDay.");
                        }
                    }
                },
            ],
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
