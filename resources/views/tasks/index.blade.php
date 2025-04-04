@extends('layouts.app')

@section('content')
    <h1>Tasks</h1>

    <a href="{{ route('tasks.create') }}">➕ Add New Task</a>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @forelse ($tasks as $task)
        <div style="border: 1px solid #ccc; margin: 1em 0; padding: 1em;">
            <strong>{{ $task->title }}</strong> ({{ ucfirst($task->priority) }})
            <br>
            Length: {{ $task->length ?? 0 }} minutes
            <br>
            Done: {{ $task->done ? '✔️ Yes' : '❌ No' }}
            <br>
            Assignees: {{ is_array($task->assignees) ? implode(', ', $task->assignees) : '-' }}
            <br>
            Scheduled Day: {{ \Carbon\Carbon::parse($task->scheduled_day)->format('Y-m-d') }}

            <div style="margin-top: 10px;">
                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('Are you sure you want to delete this task?')" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">🗑️ Delete</button>
                </form>

                <a href="{{ route('tasks.edit', $task->id) }}">✏️ Edit</a>

                <form method="POST" action="{{ route('tasks.duplicate', $task->id) }}" style="display:inline;">
                    @csrf
                    <button type="submit">📄 Duplicate</button>
                </form>
            </div>

            <form method="POST" action="{{ route('tasks.reschedule', $task->id) }}" style="margin-top: 10px;">
                @csrf
                <label>Reschedule to (weekdays only):</label>
                <input type="date" name="scheduled_day" required>
                <button type="submit">📆 Reschedule</button>
            </form>
        </div>
    @empty
        <p>No tasks found.</p>
    @endforelse
@endsection
