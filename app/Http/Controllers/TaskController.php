<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function index(): View
    {
        return view('tasks.index', [
            'tasks' => $this->taskRepository->all(),
        ]);
    }

    public function create(): View
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskRepository->create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->taskRepository->update($task->id, $request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->taskRepository->delete($id);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    public function reschedule(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'scheduled_day' => ['required', 'date'],
        ]);

        try {
            $this->taskRepository->reschedule($id, $request->input('scheduled_day'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task successfully rescheduled.');
    }

    public function duplicate(int $id): RedirectResponse
    {
        $this->taskRepository->duplicate($id);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task duplicated.');
    }

    public function weekly(Request $request): View
    {
        $current = Carbon::parse($request->get('date', now()));

        $startOfWeek = $current->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->addDays(4); // Friday

        $tasks = $this->taskRepository->getTasksBetween($startOfWeek, $endOfWeek);

        $grouped = collect();
        foreach (range(0, 4) as $i) {
            $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $grouped[$date] = $tasks->filter(fn ($task) => $task->scheduled_day->format('Y-m-d') === $date);
        }

        return view('tasks.weekly', [
            'weekStart' => $startOfWeek,
            'weekEnd' => $endOfWeek,
            'groupedTasks' => $grouped,
        ]);
    }
}
