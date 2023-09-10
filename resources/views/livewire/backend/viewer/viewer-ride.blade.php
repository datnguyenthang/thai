<div>
    <h1>{{ trans('backend.listride') }}</h1>
    <input type="text" wire:model.debounce.500ms="search" placeholder="{{ trans('backend.search') }}...">

    <table class="table table-striped datatable-table">
        <thead>
            <tr>
                <th>Id</th>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>{{ trans('backend.ridename') }}</th>
                <th wire:click="sortBy('fromLocation')"><i class="fas fa-sort"></i>{{ trans('backend.fromlocation') }}</th>
                <th wire:click="sortBy('toLocation')"><i class="fas fa-sort"></i>{{ trans('backend.tolocation') }}</th>
                <th wire:click="sortBy('departDate')"><i class="fas fa-sort"></i>{{ trans('backend.departdate') }}</th>
                <th wire:click="sortBy('departTime')"><i class="fas fa-sort"></i>{{ trans('backend.departtime') }}</th>
                <th wire:click="sortBy('returnTime')"><i class="fas fa-sort"></i>{{ trans('backend.returntime') }}</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>{{ trans('backend.ridestatus') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rides as $ride)
                <tr>
                    <td>{{ $ride->id }}</td>
                    <td>{{ $ride->name }}</td>
                    <td>{{ $ride->fromLocation }}</td>
                    <td>{{ $ride->toLocation }}</td>
                    <td>{{ $ride->departDate }}</td>
                    <td>{{ $ride->departTime }}</td>
                    <td>{{ $ride->returnTime }}</td>
                    <td>{{ RIDESTATUS[$ride->status] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $rides->links() }}
    </div>

    {{--Show modal boostrap to detail ride--}}
    <div class="modal fade show" tabindex="-1" 
        style="display: @if($showModal === true) block @else none @endif;" role="dialog" wire:model="viewRide">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('backend.ridedetail') }}</h5>
                    <button wire:click="$set('showModal', false)">{{ trans('backend.close') }}</button>
                </div>
                <div class="modal-body">
                    @if($showModal === true &&  $rideDetail)
                        <p>{{ trans('backend.id') }} : {{ $rideDetail['id'] }}</p>
                        <p>{{ trans('backend.ridename') }} : {{ $rideDetail['name'] }}</p>
                        <p>{{ trans('backend.fromlocation') }} : {{ $rideDetail['fromLocation'] }}</p>
                        <p>{{ trans('backend.tolocation') }} : {{ $rideDetail['toLocation'] }}</p>
                        <p>{{ trans('backend.departtime') }} : {{ $rideDetail['departTime'] }}</p>
                        <p>{{ trans('backend.returntime') }}: {{ $rideDetail['returnTime'] }}</p>
                        <p>{{ trans('backend.departdate') }}: {{ $rideDetail['departDate'] }}</p>
                        <p>{{ trans('backend.ridestatus') }} : {{ RIDESTATUS[$rideDetail['status']] }}</p>
                        <!-- Other details -->
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;"></div>
    </div>
</div>