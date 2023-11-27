<div>
    <h2>Permission Matrix</h2>

    <form wire:submit.prevent="saveMatrix">
        <div class="table-responsive"> 
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th class="sticky-column fw-bold">Roles</th>
                        @foreach ($permissions as $permission)
                            <th>{{ $permission->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td class="sticky-column fw-bold">{{ $role->name }}</td>
                            @foreach ($permissions as $permission)
                                <td>
                                    <input type="checkbox" wire:model="matrix.{{ $role->id }}.{{ $permission->id }}">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button wire:click="saveMatrix" class="btn btn-primary">Save</button>
    </form>
    <style>
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }
    
        .sticky-column {
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: #f8f9fa; /* Adjust the background color as needed */
        }
    </style>
</div>
