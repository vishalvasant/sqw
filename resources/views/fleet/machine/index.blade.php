@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Machines Without Drivers</h1>
    <a href="{{ route('machines.create') }}" class="btn btn-primary mb-3">Add Machine</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Per Hour Cost</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($machines as $machine)
                <tr>
                    <td>{{ $machine->name }}</td>
                    <td>{{ $machine->per_hour_cost }}</td>
                    <td>
                        <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('machines.destroy', $machine->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
