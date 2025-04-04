<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Carbon\Carbon;

class TaskRepository implements TaskRepositoryInterface
{
    public function all()
    {
        return Task::all();
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function find($id)
    {
        return Task::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task;
    }

    public function delete($id)
    {
        return Task::destroy($id);
    }

    public function reschedule(int $id, string $newDate)
    {
        $task = Task::findOrFail($id);

        // Ensure it's a weekday
        $dayOfWeek = Carbon::parse($newDate)->dayOfWeek;
        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            throw new \Exception('Csak hétköznapokra lehet ütemezni.');
        }

        $task->scheduled_day = $newDate;
        $task->save();

        return $task;
    }

    public function duplicate(int $id)
    {
        $original = Task::findOrFail($id);
        $copy = $original->replicate(); // Clone all fields except ID, created_at, updated_at
        $copy->title = $copy->title . ' (másolat)';
        $copy->done = false;
        $copy->save();

        return $copy;
    }
}
