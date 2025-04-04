<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Edit Task</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block font-medium">Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $task->title) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label for="length" class="block font-medium">Length (minutes)</label>
                <input type="number" id="length" name="length" value="{{ old('length', $task->length) }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block font-medium">Is Done?</label>
                <label><input type="radio" name="done" value="1" {{ old('done', $task->done) == 1 ? 'checked' : '' }}> Yes</label>
                <label class="ml-4"><input type="radio" name="done" value="0" {{ old('done', $task->done) == 0 ? 'checked' : '' }}> No</label>
            </div>

            <div>
                <label for="assignees" class="block font-medium">Assignees (comma-separated, max 4)</label>
                <input type="text" id="assignees" name="assignees"
                       value="{{ old('assignees', is_array($task->assignees) ? implode(', ', $task->assignees) : '') }}"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="priority" class="block font-medium">Priority</label>
                <select id="priority" name="priority" class="w-full border rounded px-3 py-2">
                    @foreach (['low', 'normal', 'high'] as $value)
                        <option value="{{ $value }}" {{ old('priority', $task->priority) === $value ? 'selected' : '' }}>
                            {{ ucfirst($value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="scheduled_day" class="block font-medium">Scheduled Day *</label>
                <input type="date" id="scheduled_day" name="scheduled_day"
                       value="{{ old('scheduled_day', \Carbon\Carbon::parse($task->scheduled_day)->format('Y-m-d')) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    💾 Update Task
                </button>
                <a href="{{ route('tasks.index') }}" class="ml-3 text-sm text-gray-500 hover:underline">← Back</a>
            </div>
        </form>
    </div>
</x-app-layout>
