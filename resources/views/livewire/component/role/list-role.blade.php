<div>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/role/create/0" class="btn btn-info">{{ trans('backend.createrole') }}</a>
    
    <table class="table table-striped datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.username') }}</th>
                <th><i class="fas fa-sort"></i>{{ trans('backend.userstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ USERSTATUS[$role->status] }}</td>
                    <td>
                        <a class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        href="/role/create/{{ $role->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $roles->links() }}
    </div>
</div>