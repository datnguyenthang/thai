<div>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/user/create/0" class="btn btn-info">{{ trans('backend.createuser') }}</a>
    
    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.username') }}</th>
                <th wire:click="sortBy('email')"><i class="fas fa-sort"></i>{{ trans('backend.useremail') }}</th>
                <th wire:click="sortBy('role')"><i class="fas fa-sort"></i>{{ trans('backend.userrole') }}</th>
                <th wire:click="sortBy('agentId')"><i class="fas fa-sort"></i>{{ trans('backend.useragent') }}</th>
                <th><i class="fas fa-sort"></i>{{ trans('backend.userstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>@if($user->agentId) {{ $listAgent[$user->agentId] }} @endif</td>
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