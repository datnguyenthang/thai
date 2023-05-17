<div>
    <input type="text" wire:model.debounce.500ms="search" placeholder="Search...">
    <a href="/user/create/0" class="btn btn-info">Create User</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>Name</th>
                <th wire:click="sortBy('email')"><i class="fas fa-sort"></i>Email</th>
                <th wire:click="sortBy('role')"><i class="fas fa-sort"></i>Role</th>
                <th><i class="fas fa-sort"></i>Status</th>
                <th><i class="fas fa-tasks"></i>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createUser({{ $user->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $users->links() }}
    </div>
</div>

