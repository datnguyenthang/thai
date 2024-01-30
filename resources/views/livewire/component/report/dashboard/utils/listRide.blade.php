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
                <th>
                    <a href="#" wire:click="exportRides()">
                        <i class="fas fa-download"></i>
                    </a>
                </th>
            </tr>
            @foreach($listRides as $key => $ride)
                @php
                    if ($ride->isDepart) {
                        $class = 'table-danger';
                        $color = '';
                    } else {
                        if($ride->colorCode) {
                            $color = 'style=background-color:#'.$ride->colorCode.';';
                            $class = '';
                        } else {
                            $class = 'table-success';
                            $color = '';
                        }
                    }
                @endphp
                <tr class="{{ $ride->isDepart ? 'table-danger' : 'table-success'; }}">
                    <td {{ $color }}>{{ $ride->id }}</td>
                    <td {{ $color }}>{{ $ride->name }}</td>
                    <td {{ $color }}>{{ $ride->fromLocationName }}</td>
                    <td {{ $color }}>{{ $ride->toLocationName }}</td>
                    <td {{ $color }}>{{ $ride->departDate . ' ' . $ride->departTime }}</td>
                    <td {{ $color }}><strong class="text-success">{{ $ride->totalCustomerConfirm }}</strong>/<strong>{{ $ride->totalCustomer }}</strong></td>
                    <td {{ $color }}><strong class="text-success">{{ $ride->totalOrderConfirm }}</strong>/<strong>{{ $ride->totalOrder }}</strong></td>
                    <td {{ $color }}>
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