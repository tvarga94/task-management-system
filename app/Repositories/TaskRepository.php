<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(): Collection
    {
        return Task::all();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function find(int $id): Task
    {
        return Task::findOrFail($id);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);

        return $task;
    }

    public function delete(int $id): bool|int
    {
        return Task::destroy($id);
    }

    public function reschedule(int $id, string $newDate): Task
    {
        $task = Task::findOrFail($id);

        $dayOfWeek = Carbon::parse($newDate)->dayOfWeek;
        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            throw new \Exception('Only weekdays (Mon–Fri) are allowed.');
        }

        $task->scheduled_day = $newDate;
        $task->save();

        return $task;
    }

    public function duplicate(int $id): Task
    {
        $original = Task::findOrFail($id);
        $copy = $original->replicate();
        $copy->title = $copy->title . ' (copy)';
        $copy->done = false;
        $copy->save();

        return $copy;
    }
}
