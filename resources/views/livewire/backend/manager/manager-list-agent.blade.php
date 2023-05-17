<div>
    <h1>List Agent</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="Search...">
    <a href="/agent/create/0" class="btn btn-info">Create agent</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>Name</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>Status</th>
                <th><i class="fas fa-tasks"></i>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
                <tr>
                    <td>{{ $agent->name }}</td>
                    <td>{{ AGENTSTATUS[$agent->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createAgent({{ $agent->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $agents->links() }}
    </div>
</div>

