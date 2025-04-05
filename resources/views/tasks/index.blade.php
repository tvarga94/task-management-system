<x-app-layout>
    <div class="flex justify-center">
        <div class="w-full max-w-2xl px-4 py-10 space-y-8">

            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-4">
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-800">Task Manager</h1>
                <a href="{{ route('tasks.create') }}"
                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded shadow-sm">
                    ➕ New Task
                </a>
            </div>

            <!-- Flash Message -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Task Cards -->
            @forelse ($tasks as $task)
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-5 space-y-4">
                    <div class="space-y-1">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            {{ $task->title }}
                            <span class="text-sm font-medium text-gray-500">({{ ucfirst($task->priority) }})</span>
                        </h2>

                        <p class="text-sm text-gray-600"><strong>Length:</strong> {{ $task->length ?? 0 }} minutes</p>
                        <p class="text-sm text-gray-600">
                            <strong>Done:</strong>
                            <span class="{{ $task->done ? 'text-green-600' : 'text-red-600' }}">
                                {{ $task->done ? 'Yes' : 'No' }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-600"><strong>Assignees:</strong> {{ is_array($task->assignees) ? implode(', ', $task->assignees) : '-' }}</p>
                        <p class="text-sm text-gray-600"><strong>Scheduled Day:</strong> {{ $task->scheduled_day->format('Y-m-d') }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-4 items-center pt-2 border-t pt-4">
                        <a href="{{ route('tasks.edit', $task->id) }}"
                           class="text-sm text-blue-600 hover:underline">✏️ Edit</a>

                        <form method="POST" action="{{ route('tasks.duplicate', $task->id) }}">
                            @csrf
                            <button type="submit" class="text-sm text-yellow-600 hover:underline">📄 Duplicate</button>
                        </form>

                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this task?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">🗑️ Delete</button>
                        </form>
                    </div>

                    <!-- Reschedule -->
                    <form method="POST" action="{{ route('tasks.reschedule', $task->id) }}"
                          class="flex flex-col sm:flex-row items-center gap-3 mt-3">
                        @csrf
                        <label for="scheduled_day_{{ $task->id }}" class="text-sm font-medium text-gray-700">
                            Reschedule:
                        </label>
                        <input
                            type="date"
                            name="scheduled_day"
                            id="scheduled_day_{{ $task->id }}"
                            value="{{ $task->scheduled_day->format('Y-m-d') }}"
                            class="border rounded px-3 py-1 text-sm w-full sm:w-auto"
                            required
                        >
                        <button type="submit"
                                class="bg-green-100 hover:bg-green-200 border border-green-400 text-sm px-3 py-1 rounded">
                            Save New Date
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center text-gray-600 pt-10">
                    No tasks found.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
