@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'user',
    'pageTitle' => 'Users'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Users Table</h4>
                        @can('create-user')
                        <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    @can('edit-user' && 'delete-user')
                                    <th class="text-right">Actions</th>
                                    @endcan
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->name }}</td>
                                        @can('edit-user' && 'delete-user')
                                        <td class="text-right">
                                            <!-- Edit button -->
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm">Edit</a>
                                            
                                            <!-- Delete form -->
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection