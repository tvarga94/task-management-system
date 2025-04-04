<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 space-y-6">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-semibold text-gray-800">Task Manager</h1>
            <a href="{{ route('tasks.create') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded">
                ➕ New Task
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($tasks as $task)
            <div class="bg-white shadow border rounded p-5 space-y-2">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $task->title }}
                        <span class="ml-2 text-sm text-gray-500 font-medium">({{ ucfirst($task->priority) }})</span>
                    </h2>
                    <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($task->scheduled_day)->format('Y-m-d') }}</span>
                </div>

                <p><strong>Length:</strong> {{ $task->length ?? 0 }} minutes</p>
                <p><strong>Done:</strong> {{ $task->done ? '✔️ Yes' : '❌ No' }}</p>
                <p><strong>Assignees:</strong> {{ is_array($task->assignees) ? implode(', ', $task->assignees) : '-' }}</p>

                <div class="flex flex-wrap items-center gap-4 pt-4 border-t mt-4">
                    <a href="{{ route('tasks.edit', $task->id) }}" class="text-blue-600 hover:underline text-sm">✏️ Edit</a>

                    <form method="POST" action="{{ route('tasks.duplicate', $task->id) }}">
                        @csrf
                        <button type="submit" class="text-yellow-600 hover:underline text-sm">📄 Duplicate</button>
                    </form>

                    <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                          onsubmit="return confirm('Are you sure you want to delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-sm">🗑️ Delete</button>
                    </form>
                </div>

                <form method="POST" action="{{ route('tasks.reschedule', $task->id) }}" class="mt-4 flex flex-col sm:flex-row items-center gap-2">
                    @csrf
                    <label for="scheduled_day_{{ $task->id }}" class="text-sm font-medium text-gray-700">Reschedule to:</label>
                    <input type="date" name="scheduled_day" id="scheduled_day_{{ $task->id }}"
                           class="border rounded px-3 py-1 text-sm w-full sm:w-auto" required>
                    <button type="submit"
                            class="bg-gray-200 hover:bg-gray-300 text-sm px-3 py-1 rounded">
                        📆 Reschedule
                    </button>
                </form>
            </div>
        @empty
            <div class="text-gray-600 text-center mt-10">
                No tasks found.
            </div>
        @endforelse

    </div>
</x-app-layout>
