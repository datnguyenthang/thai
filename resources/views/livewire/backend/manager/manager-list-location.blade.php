<div>
    <h1>{{ trans('backend.listlocation') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">
    <a href="/location/create/0" class="btn btn-info">{{ trans('backend.createlocation') }}</a>
    
    <table class="table table-striped datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.locationname') }}</th>
                <th wire:click="sortBy('nameOffice')"><i class="fas fa-sort"></i>{{ trans('backend.locationnameoffice') }}</th>
                <th wire:click="sortBy('googleMapUrl')"><i class="fas fa-sort"></i>{{ trans('backend.locationgooglemapurl') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.locationstatus') }}</th>
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($locations as $location)
                <tr>
                    <td>{{ $location->name }}</td>
                    <td>{{ $location->nameOffice }}</td>
                    <td>{{ $location->googleMapUrl }}</td>
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

