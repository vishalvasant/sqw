@extends('layouts.admin')

@section('page-title', 'PO-PR Report')

@section('content')
<div class="card">

<!-- Summary Section -->
    <div class="card-body">
        <h5>Report Summary ({{ request('from_date') }} to {{ request('to_date') }})</h5>
        <ul>
            
        </ul>
    </div>
    
    <!-- Report Table -->
    <div class="card-body">
        @if(isset($tasks) && count($tasks) > 0)
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task Title</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->assignee->name }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
                            <span class="badge badge-{{ $task->status == 'completed' ? 'success' : ($task->status == 'in-progress' ? 'warning' : 'danger') }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted">No purchase requests found for the selected date range.</p>
        @endif
    </div>
</div>
@endsection
