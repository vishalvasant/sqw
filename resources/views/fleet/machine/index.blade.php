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
                        <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-warning"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('machines.destroy', $machine->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="if(confirm('Are you sure you want to delete this?')) { this.form.submit(); }"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
