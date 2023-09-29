<div>
    <!-- Content Row -->
    <div class="row">

        <!-- List Rides -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <div class="col-md-2">
                        <h6 class="m-0 font-weight-bold text-primary">Rides in day</h6>
                    </div>
                    <div class="col-md-2">
                        <label>Depart</label>
                        <select id="fromLocation" name="fromLocation" class="form-control form-select" wire:model="fromLocation" placeholder="{{ trans('messages.pickup') }}">
                            <option value=""></option>
                            @foreach($fromLocationList as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Destination</label>
                        <select id="toLocation" name="toLocation" class="form-control form-select" wire:model="toLocation" placeholder="{{ trans('messages.dropoff') }}">
                            <option value=""></option>
                            @foreach($toLocationList as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>From</label>
                        <input id="fromDate" name="fromDate" wire:model="fromDate" class="form-control" type="date" required>
                    </div>
                    <div class="col-md-2">
                        <label>To</label>
                        <input id="toDate" name="toDate" wire:model="toDate" class="form-control" type="date" required>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center text-center">
                        <label>Has ordered?</label>
                        <input id="hasOrdered" type="checkbox" wire:model="hasOrdered" class="form-control form-check-input">
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="height: 30rem;">
                    <div class="row">
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Depart Time</th>
                                <th>Total Customer</th>
                                <th>Total Order</th>
                                <th>Total Money</th>
                                <th>
                                    <a href="#" wire:click="exportRides()">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </th>
                            </tr>
                            @foreach($listRides as $key => $ride)
                                <tr class="{{ $ride->isDepart ? 'table-danger' : 'table-success'; }}">
                                    <td>{{ $ride->id }}</td>
                                    <td>{{ $ride->name }}</td>
                                    <td>{{ $ride->fromLocationName }}</td>
                                    <td>{{ $ride->toLocationName }}</td>
                                    <td>{{ $ride->departDate . ' ' . $ride->departTime }}</td>
                                    <td><strong class="text-success">{{ $ride->totalCustomerConfirm }}</strong>/<strong>{{ $ride->totalCustomer }}</strong></td>
                                    <td><strong class="text-success">{{ $ride->totalOrderConfirm }}</strong>/<strong>{{ $ride->totalOrder }}</strong></td>
                                    <td><strong class="text-success">{{ round($ride->totalMoneyConfirm) }}</strong>/<strong>{{ round($ride->totalMoney) }}</strong></td>
                                    <td>
                                        <a href="#" wire:click="displayRide({{ $ride->id }})">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" wire:click="exportRides({{ $ride->id }})">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        {{ $listRides->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!----Modal Ride------->
    {{--Show modal boostrap to quick view detail order--}}
    <div class="modal fade show" tabindex="-1" wire:model="boardingPass" wire:key="modal-{{ $rideId }}"
        style="display: @if($showModal === true) block @else none @endif;" role="dialog">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('backend.ridedetail') }}</h5>
                    <button class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">

                    @if($showModal === true && $listPassengers)
                        <div class="row">
                            <table class="table table-striped">
                                <tr>
                                    <th>Order Code</th>
                                    <th>Phone</th>
                                    {{--<th>Email</th>--}}
                                    <th>Adult Quantity</th>
                                    <th>Children Quantity</th>
                                    <th>Pickup</th>
                                    <th>Dropoff</th>
                                    <th>Full Name</th>
                                    <th>Customer Type</th>
                                    <th>User Name</th>
                                    <th>Price</th>
                                    <th>Payment Status</th>
                                    <th>Status</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                @foreach ($listPassengers as $listPassenger)
                                    <tr>
                                        <td>{{ $listPassenger->code }}</td>
                                        <td>{{ $listPassenger->phone }}</td>
                                        {{--<td>{{ $listPassenger->email }}</td>--}}
                                        <td>{{ $listPassenger->adultQuantity }}</td>
                                        <td>{{ $listPassenger->childrenQuantity }}</td>
                                        <td>{{ $listPassenger->pickup }}</td>
                                        <td>{{ $listPassenger->dropoff }}</td>
                                        <td>{{ $listPassenger->fullname }}</td>
                                        <td>{{ $listPassenger->CustomerType }}</td>
                                        <td>{{ $listPassenger->name }}</td>
                                        <th>{{ round($listPassenger->price) }}</th>
                                        <td>{{ PAYMENTSTATUS[$listPassenger->paymentStatus] }}</td>
                                        <td>{{ ORDERSTATUS[$listPassenger->status] }}</td>
                                        <td>
                                            <a class="btn bg_own_color text-light"
                                                href="/{{ $role }}processorder/{{$listPassenger->orderId}}"
                                                target="_blank"
                                            >
                                                {{ trans('backend.vieworder') }}
                                            </a>
                                        </td>
                                        <td>
                                            <button class="btn bg_own_color text-light"
                                                wire:key="passenger-{{ $listPassenger->orderTicketId }}"
                                                wire:click="boardingPass({{ $rideId }}, {{ $listPassenger->orderTicketId }})"
                                                wire:loading.attr="disabled">
                                                    {{ trans('backend.download') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    @endif
                    <div class="modal-footer">
                        <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module">

    </script>
</div>
