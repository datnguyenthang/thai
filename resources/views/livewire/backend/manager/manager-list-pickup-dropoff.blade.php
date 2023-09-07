<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <h1>{{ trans('backend.listpkdp') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/pkdp/create/0" class="btn btn-info">{{ trans('backend.createpkdp') }}</a>
    
    <table class="table table-striped datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('id')"><i class="fas fa-sort"></i>{{ trans('backend.id') }}</th>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.pkdpname') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.pkdpstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pkdps as $pkdp)
                <tr>
                    <td>{{ $pkdp->id }}</td>
                    <td>{{ $pkdp->name }}</td>
                    <td>{{ PICKUPDROPOFFSTATUS[$pkdp->status] }}</td>
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createPkdp({{ $pkdp->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $pkdps->links() }}
    </div>
</div>
