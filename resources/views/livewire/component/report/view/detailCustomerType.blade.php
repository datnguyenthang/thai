<section class="mb-4">
    <div class="card">
        <div class="card-header text-center py-3 bg-dark text-dark bg-opacity-10">
            <h5 class="mb-0 text-center text-success">
                <strong>Customer Type</strong>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Online</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Local</th>
                            <th scope="col">Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">Order</th>
                            <td>{{ $paxOrderCustomerType->filter(fn ($item) => is_null($item['type']))->first()['pax'] ?? 0 }}</td>
                            <td>{{ $paxOrderCustomerType->filter(fn ($item) => $item['type'] === 0)->first()['pax'] ?? 0 }}</td>
                            <td>{{ $paxOrderCustomerType->filter(fn ($item) => $item['type'] == 1)->first()['pax'] ?? 0 }}</td>
                            <td>{{ $paxOrderCustomerType->filter(fn ($item) => $item['type'] == 2)->first()['pax'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Traveled</th>
                            <td>{{ $paxRideCustomerType->filter(fn ($item) => is_null($item['type']))->first()['pax'] ?? 0 }}</td>
                            <td>{{ $paxRideCustomerType->filter(fn ($item) => $item['type'] === 0)->first()['pax'] ?? 0 }}</td>
                            <td>{{ $paxRideCustomerType->filter(fn ($item) => $item['type'] == 1)->first()['pax'] ?? 0 }}</td>
                            <td>{{ $paxRideCustomerType->filter(fn ($item) => $item['type'] == 2)->first()['pax'] ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>