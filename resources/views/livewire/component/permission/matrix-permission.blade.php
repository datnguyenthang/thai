<div>
    <h2>Permission Matrix</h2>

    <form wire:submit.prevent="saveMatrix">
        <table class="table">
            <thead>
                <tr>
                    <th>Roles</th>
                    @foreach ($permissions as $permission)
                        <th>{{ $permission->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        @foreach ($permissions as $permission)
                            <td>
                                <input type="checkbox" wire:model="matrix.{{ $role->id }}.{{ $permission->id }}">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button wire:click="saveMatrix" class="btn btn-primary">Save</button>
    </form>
</div>
