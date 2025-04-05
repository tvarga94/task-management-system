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

    /**
     * @throws \Exception
     */
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
        $assignees = $original->assignees ?? [];
        $scheduledDay = Carbon::parse($original->scheduled_day);
        $totalLength = $original->length ?? 0;

        $available = 480;

        foreach ($assignees as $assignee) {
            $assigned = Task::whereJsonContains('assignees', $assignee)
                ->whereDate('scheduled_day', $scheduledDay->format('Y-m-d'))
                ->sum('length');

            $remaining = max(0, 480 - $assigned);
            $available = min($available, $remaining);
        }

        if ($available <= 0) {
            $nextDay = $this->getNextWeekday(clone $scheduledDay);

            $copy = $original->replicate();
            $copy->title .= ' (' . $nextDay->format('Y-m-d') . ')';
            $copy->scheduled_day = $nextDay->format('Y-m-d');
            $copy->done = false;
            $copy->save();

            return $copy;
        }

        if ($available >= $totalLength) {
            $copy = $original->replicate();
            $copy->title .= ' (' . $scheduledDay->format('Y-m-d') . ')';
            $copy->done = false;
            $copy->save();

            return $copy;
        }

        $part1 = $original->replicate();
        $part1->title .= ' (' . $scheduledDay->format('Y-m-d') . ')';
        $part1->length = $available;
        $part1->done = false;
        $part1->save();

        $nextDay = $this->getNextWeekday(clone $scheduledDay);

        $part2 = $original->replicate();
        $part2->title .= ' (' . $nextDay->format('Y-m-d') . ')';
        $part2->length = $totalLength - $available;
        $part2->scheduled_day = $nextDay->format('Y-m-d');
        $part2->done = false;
        $part2->save();

        return $part1;
    }

    public function getTasksBetween(Carbon $start, Carbon $end): Collection
    {
        return Task::whereBetween('scheduled_day', [$start->toDateString(), $end->toDateString()])->get();
    }

    private function getNextWeekday(Carbon $date): Carbon
    {
        $date->addDay();

        while ($date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }
}
