@extends('layouts.admin')

@section('page-title', 'Edit Task')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Task</h3>
    </div>
    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
            </div>

            <div class="form-group">
                <label for="description">Task Description</label>
                <textarea name="description" class="form-control" rows="4" required>{{ $task->description }}</textarea>
            </div>
            @if ($task->file_path)
                <div class="mt-3">
                    @php
                        $fileExtension = pathinfo($task->file_path, PATHINFO_EXTENSION);
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                    @endphp
                    
                    @if (in_array($fileExtension, $imageExtensions))
                        <img src="{{ asset('storage/' . $task->file_path) }}" alt="Task File" class="img-thumbnail" style="width: 150px;">
                    @else
                        <a href="{{ asset('storage/' . $task->file_path) }}" target="_blank" class="btn btn-primary btn-sm">Download File</a>
                    @endif

                    <!-- Delete File Button -->
                    <button type="button" class="btn btn-danger btn-sm delete-file" data-task-id="{{ $task->id }}"  onclick="return confirm('Are you sure?')">Delete File</button>
                    <input type="hidden" id="taskId" name="taskId" value="{{$task->id}}">
                </div>
            @endif
            <div class="form-group">
                <label for="assigned_to">Assign To</label>
                <select name="assigned_to" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" name="due_date" class="form-control" value="{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}" required>
            </div>
        </div>
        
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Task</button>
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
<!-- JavaScript for Delete File -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    let deleteButton = document.querySelector(".delete-file");

    if (deleteButton) {
        deleteButton.addEventListener("click", function () {
            let taskId = document.querySelector("#taskId").value;

            if (confirm("Are you sure you want to delete the attached file?")) {
                fetch(`/tasks/${taskId}/delete-file`, {
                    method: "DELETE",
                    headers: {
                        "XSRF-TOKEN": <?php echo json_encode(csrf_token()); ?>
                    }
                }).then(response => location.reload());
            }
        });
    }
});
</script>