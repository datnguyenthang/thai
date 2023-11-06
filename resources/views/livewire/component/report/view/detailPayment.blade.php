<section class="mt-2">
    <div class="card">
        <div class="card-header text-center py-3 bg-muted text-dark bg-opacity-10">
            <h5 class="mb-0 text-center text-warning">
                <strong>OVERALL TO DEPART DAY</strong>
            </h5>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-xl-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-header text-center py-3 bg-muted text-dark bg-opacity-10">
                    <h5 class="mb-0 text-center text-warning">
                        <strong>Customer Type</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <table class="table table-striped datatable-table table-secondary mt-3">
                            <tbody>
                                @if (!empty($customerTypePayments))
                                    @foreach($customerTypePayments as $payment)
                                        <tr class='table-danger'>
                                            <td>{{ $payment->name ?? 'ONLINE' }}</td>
                                            <td>฿{{ number_format($payment->amount, 0) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center text-danger" colspan="10">{{ trans('backend.nodatafound') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-sm-6 col-12 mb-4">
            <div class="card">
                <div class="card-header text-center py-3 bg-muted text-dark bg-opacity-10">
                    <h5 class="mb-0 text-center text-warning">
                        <strong>Payment Method</strong>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between px-md-1">
                        <table class="table table-striped datatable-table table-secondary mt-3">
                            <tbody>
                                @if (!empty($paymentMethodDetails))
                                    @foreach($paymentMethodDetails as $paymentMethod)
                                        <tr class='table-success'>
                                            <td>{{ $paymentMethod->name ? ucwords($paymentMethod->name) : 'NOT PAID' }}</td>
                                            <td>฿{{ number_format($paymentMethod->amount, 0) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr></tr>
                                    <tr class='table-success'>
                                        <td>Total</td>
                                        <td>฿{{ number_format($paymentMethodDetails->sum('amount'), 0) }}</td>
                                    </tr>

                                @else
                                    <tr>
                                        <td class="text-center text-danger" colspan="10">{{ trans('backend.nodatafound') }}</td>
                                    </tr>
                                @endif 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>