<div>
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Amount Order (Today)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAmountThisDay }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Order Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrderThisDay }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Awaiting Confirmation</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingComfirmation }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- List Rides -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <div class="col-md-2">
                        <h6 class="m-0 font-weight-bold text-primary">Rides in day</h6>
                    </div>
                    <div class="col-md-2">
                        <label>Depart</label>
                        <select id="fromLocation" name="fromLocation" class="form-control" wire:model="fromLocation" placeholder="{{ trans('messages.pickup') }}">
                            <option value=""></option>
                            @foreach($fromLocationList as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Destination</label>
                        <select id="toLocation" name="toLocation" class="form-control" wire:model="toLocation" placeholder="{{ trans('messages.dropoff') }}">
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
                </div>
                <!-- Card Body -->
                <div class="card-body" style="height: 30rem;">
                    <div class="row">
                        <table class="table">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Depart Time</th>
                                <th>Total Customer</th>
                                <th>Total Confirmed Customer</th>
                                <th>
                                    <a href="#" wire:click="exportRides()">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </th>
                            </tr>
                            @foreach($listRides as $key => $ride)
                                <tr class="{{ $ride->isDepart ? 'table-danger' : 'table-success'; }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $ride->name }}</td>
                                    <td>{{ $ride->fromLocationName }}</td>
                                    <td>{{ $ride->toLocationName }}</td>
                                    <td>{{ $ride->departDate . ' ' . $ride->departTime }}</td>
                                    <td>{{ $ride->totalCustomer }}</td>
                                    <td>{{ $ride->totalCustomerConfirmed }}</td>
                                    <td>
                                        <a href="#" wire:click="exportRides({{ $ride->id }})">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue in day</h6>
                    <div class="dropdown no-arrow">
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 33rem;">
                    <div class="chart-container pt-4 pb-2" style="position: relative;">
                        <canvas id="revenueChart" style=""></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module">
        const data = {
            labels: [
                'Confirm',
                'Not confirmed'
            ],
            datasets: [{
                label: 'Revenue in day',
                data: [
                        {{ $revenue->priceConfirmed ? $revenue->priceConfirmed : 0}}, 
                        {{ $revenue->priceNotConfirmed ? $revenue->priceNotConfirmed: 0}}
                    ],

                hoverOffset: 4
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {}
        };

        const myChart = new Chart(
            document.getElementById('revenueChart'),
            config
        );
    </script>
</div>
