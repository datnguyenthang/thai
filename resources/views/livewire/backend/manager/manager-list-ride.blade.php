<div>
    <input type="text" wire:model.debounce.500ms="search" placeholder="Search...">
    <a href="/ride/create/0" class="btn btn-info">Create New Ride</a>
    <a href="/ride/massiveCreate" class="btn btn-success">Massive Create Ride</a>

    <table class="table datatable-table">
        <thead>
            <tr>
                <th>Id</th>
                <th wire:click="sortBy('name')"><i class="fas fa-sort"></i>Name</th>
                <th wire:click="sortBy('fromLocation')"><i class="fas fa-sort"></i>From Location</th>
                <th wire:click="sortBy('toLocation')"><i class="fas fa-sort"></i>To Location</th>
                <th wire:click="sortBy('departDate')"><i class="fas fa-sort"></i>Depart Date</th>
                <th wire:click="sortBy('departTime')"><i class="fas fa-sort"></i>Depart Time</th>
                <th wire:click="sortBy('returnTime')"><i class="fas fa-sort"></i>Return Time</th>
                <th wire:click="sortBy('status')"><i class="fas fa-sort"></i>Status</th>
                <th><i class="fas fa-tasks"></i>Action</th>
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
                    <td>
                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="viewRide({{ $ride->id }})">
                            <i class="fa fa-eye"></i>
                        </button>

                        <button class="call-btn btn btn-outline-primary btn-floating btn-sm"
                        wire:click="createRide({{ $ride->id }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
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
                    <h5 class="modal-title">Row Details</h5>
                    <button wire:click="$set('showModal', false)">Close</button>
                </div>
                <div class="modal-body">
                    @if($rideDetail)
                        <p>ID: {{ $rideDetail['id'] }}</p>
                        <p>Name: {{ $rideDetail['name'] }}</p>
                        <p>From Location: {{ $rideDetail['fromLocation'] }}</p>
                        <p>To Location: {{ $rideDetail['toLocation'] }}</p>
                        <p>Depart Time: {{ $rideDetail['departTime'] }}</p>
                        <p>Return Time: {{ $rideDetail['returnTime'] }}</p>
                        <p>Depart Date: {{ $rideDetail['departDate'] }}</p>
                        <p>Status : {{ RIDESTATUS[$rideDetail['status']] }}</p>
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


