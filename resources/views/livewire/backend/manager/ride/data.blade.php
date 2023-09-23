<table class="table datatable-table table-secondary table-striped mt-3">
    <thead>
        <tr>
            <th><input type="checkbox" wire:model="selectedAll" class="form-check-input" /></th>
            <th>Id</th>
            <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.ridename') }}</th>
            <th wire:click="sortBy('fromLocation')"><i class="fas fa-sort"></i>{{ trans('backend.fromlocation') }}</th>
            <th wire:click="sortBy('toLocation')"><i class="fas fa-sort"></i>{{ trans('backend.tolocation') }}</th>
            <th wire:click="sortBy('departDate')"><i class="fas fa-sort"></i>{{ trans('backend.departdate') }}</th>
            <th wire:click="sortBy('departTime')"><i class="fas fa-sort"></i>{{ trans('backend.departtime') }}</th>
            <th wire:click="sortBy('returnTime')"><i class="fas fa-sort"></i>{{ trans('backend.returntime') }}</th>
            <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.ridestatus') }}</th>
            <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (count($selected) > 0)
        <tr>      
            <td class="text-center" colspan="10">
                <span class="bg_own_color fw-bold">{{ trans('backend.selectride', ['selected' => count($selected), 'totalride' => count($listRides)]) }}</span>
                <button class="btn btn-sm bg_own_color" wire:click="resetSelected">Reset selected item</button>
            </td>
        </tr>
        @endif
        @foreach ($listRides as $ride)
            <tr>
                <td><input type="checkbox" wire:model="selected" value="{{ $ride->id }}" class="form-check-input" /></td>
                <td>{{ $ride->id }}</td>
                <td>{{ $ride->name }}</td>
                <td>{{ $ride->fromLocation }}</td>
                <td>{{ $ride->toLocation }}</td>
                <td>{{ $ride->departDate }}</td>
                <td>{{ $ride->departTime }}</td>
                <td>{{ $ride->returnTime }}</td>
                <td>{{ RIDESTATUS[$ride->status] }}</td>
                <td>
                    <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                    wire:click="viewRide({{ $ride->id }})">
                        <i class="fa fa-eye"></i>
                    </button>

                    <a class="call-btn btn btn-outline-primary btn-floating btn-sm" 
                        href="/ride/create/{{ $ride->id }}" target="_blank">
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div>
    {{ $listRides->links() }}
</div>