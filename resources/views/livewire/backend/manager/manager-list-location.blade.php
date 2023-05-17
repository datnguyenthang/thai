<div>
    <h1>List location</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="Search...">
    <a href="/location/create/0" class="btn btn-info">Create location</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>Name</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>Status</th>
                <th><i class="fas fa-tasks"></i>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $location)
                <tr>
                    <td>{{ $location->name }}</td>
                    <td>{{ LOCATIONSTATUS[$location->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createLocation({{ $location->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $locations->links() }}
    </div>
</div>

