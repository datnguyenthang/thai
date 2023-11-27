<div>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/permission/create/0" class="btn btn-info">{{ trans('backend.createpermission') }}</a>
    
    <table class="table table-striped datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.username') }}</th>
                <th><i class="fas fa-sort"></i>{{ trans('backend.userstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ USERSTATUS[$permission->status] }}</td>
                    <td>
                        <a class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        href="/permission/create/{{ $permission->id }}">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $permissions->links() }}
    </div>
</div>