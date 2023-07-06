<div class="border rounded-3 overflow-hidden p-4 mt-3">
    <!---PAYMENT--->
    <section class="">
        <h4 class="select-departure-header mb-3" >{{ trans('messages.payment') }}</h4>
        <div class="col-lg-12 mx-auto">
            
            <!-- Bank transfer info -->
            <div class="tab-pane fade show active pt-3">
                <table class="table table-bordered">
                    <thead class="table-secondary">
                        <tr>
                            <th>{{ trans('backend.paymentmethod') }}</th>
                            <th>{{ trans('backend.transactioncode') }}</th>
                            <th>{{ trans('backend.paymentmethodstatus') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>{{ $order->paymentMethod ? PAYMENTMETHOD[$order->paymentMethod] : '' }}</th>
                            <th>{{ $order->transactionCode ?? '' }}</th>
                            <th>{{ PAYMENTSTATUS[$order->paymentStatus] }}</th>
                        </tr>
                    <tbody>
                </table>
            </div> <!-- End -->
        </div>
    </section>
</div>