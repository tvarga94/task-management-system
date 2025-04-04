<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskRepositoryInterface $taskRepository) {}

    public function index()
    {
        return view('tasks.index', [
            'tasks' => $this->taskRepository->all()
        ]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskRepository->create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(int $id)
    {
        $task = $this->taskRepository->find($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(StoreTaskRequest $request, int $id)
    {
        $this->taskRepository->update($id, $request->validated());
        return redirect()->route('tasks.index')->with('success', 'Feladat frissítve');
    }

    public function destroy(int $id)
    {
        $this->taskRepository->delete($id);
        return redirect()->route('tasks.index')->with('success', 'Feladat törölve');
    }

    public function reschedule(Request $request, int $id)
    {
        $request->validate([
            'scheduled_day' => ['required', 'date']
        ]);

        try {
            $this->taskRepository->reschedule($id, $request->input('scheduled_day'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('tasks.index')->with('success', 'Feladat átütemezve');
    }

    public function duplicate(int $id)
    {
        $this->taskRepository->duplicate($id);
        return redirect()->route('tasks.index')->with('success', 'Feladat duplikálva');
    }
}
