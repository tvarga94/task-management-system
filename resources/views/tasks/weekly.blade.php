<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4">

        <!-- Header + Navigation -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Weekly Task View</h1>

            <div class="flex gap-2">
                <a href="{{ route('tasks.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded text-sm text-gray-800 shadow-sm">
                    🔙 Back to List
                </a>

                <a href="{{ route('tasks.weekly', ['date' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}"
                   class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm">
                    ⬅️ Previous
                </a>

                <a href="{{ route('tasks.weekly', ['date' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}"
                   class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded text-sm">
                    Next ➡️
                </a>
            </div>
        </div>

        <!-- Weekly Grid -->
        <div class="grid grid-cols-5 gap-4">
            @foreach($groupedTasks as $date => $tasks)
                <div class="bg-white rounded shadow-sm border p-3 flex flex-col gap-2">
                    <div class="font-semibold text-center border-b pb-2">
                        {{ \Carbon\Carbon::parse($date)->format('l') }}
                        <div class="text-xs text-gray-500">{{ $date }}</div>
                    </div>

                    @forelse($tasks as $task)
                        <div class="bg-gray-100 p-2 rounded text-sm">
                            <div class="font-medium">{{ $task->title }}</div>
                            <div class="text-xs text-gray-500">
                                {{ ucfirst($task->priority) }} • {{ $task->length ?? 0 }} min
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-gray-400 italic text-center">No tasks</div>
                    @endforelse
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
