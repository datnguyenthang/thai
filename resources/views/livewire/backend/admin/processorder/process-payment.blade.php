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
                            <th>{{ trans('backend.transactiondate') }}</th>
                            <th>{{ trans('backend.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($orderPayments))
                            @foreach ($orderPayments as $payment)
                            <tr>
                                <th>{{ $payment->paymentMethod ? ucwords($paymentMethodList->firstWhere('id', $payment->paymentMethod)->name) : '' }}</th>
                                <th>{{ $payment->transactionCode ?? '' }}</th>
                                <th>{{ !is_null($payment->paymentStatus) ? PAYMENTSTATUS[$payment->paymentStatus] : '' }}</th>
                                <th>{{ $payment->transactionDate ? date('Y-m-d H:i:s', strtotime($payment->transactionDate)) : '' }}</th>
                            </tr>
                            @endforeach
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>
                                    <button class="btn btn bg_own_color text-light" wire:click="viewOrderPayment()">{{ trans('backend.updatepayment') }}</button>

                                    @if(($payment->paymentMethod == 3 ||
                                        $payment->paymentMethod == 4) && 
                                        auth()->user()->role == 'manager')
                                        <button class="btn btn bg_own_color text-light" 
                                            wire:click="viewRefundPayment()">{{ trans('backend.refund') }}</button>
                                    @endif
                                </th>
                            </tr>
                    @endif
                    <tbody>
                </table>
            </div>
            <!-- End -->
        </div>
    </section>
</div>

{{--Show modal boostrap to quick update order payment--}}
<div class="modal fade show" tabindex="-1" 
style="display: @if($showModalPayment === true) block @else none @endif;" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">{{ trans('backend.updateorderpayment') }}</h5>
                <button class="btn-close" wire:click="$set('showModalPayment', false)"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="form-label fw-bold">{{ trans('backend.paymentmethod') }}</span>
                            <select id="paymentMethod" name="paymentMethod" class="form-select" wire:model="paymentMethod">
                                @foreach($paymentMethodList as $value)
                                    <option value="{{ $value->id }}">{{ ucwords($value->name) }}</option>
                                @endforeach
                            </select>
                            @error('paymentMethod') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @if($isTransaction)
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <span class="form-label fw-bold">{{ trans('backend.transactioncode') }}</span>
                                <input id="transactionCode" type="text" class="form-control" name="transactionCode" class="form-input" wire:model="transactionCode" />
                                @error('transactionCode') <span class="text-danger error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <span class="form-label fw-bold">{{ trans('backend.paymentmethodstatus') }}</span>
                            <select id="paymentStatus" name="paymentStatus" class="form-select" wire:model="paymentStatus">
                                @foreach(PAYMENTSTATUS as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('paymentStatus') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <span class="form-label fw-bold">{{ trans('backend.transactiondate') }}</span>
                            <input id="transactionDate" type="datetime-local" step="1" class="form-control" name="transactionDate" wire:model="transactionDate" />
                            @error('transactionDate') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <button class="btn bg_own_color text-light"
                                            wire:click="updatePayment()"
                                            wire:loading.attr="disabled">{{ trans('backend.update') }}</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="$set('showModalPayment', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>


{{--Show modal boostrap to refund order payment in Omise--}}
<div class="modal fade show" tabindex="-1" 
style="display: @if($showModalRefund === true) block @else none @endif;" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">{{ trans('backend.refundorderpayment') }}</h5>
                <button class="btn-close" wire:click="$set('showModalRefund', false)"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="form-label fw-bold">{{ trans('backend.paymentmethod') }}</span>
                            
                        </div>
                    </div>
                    
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <span class="form-label fw-bold">{{ trans('backend.paymentmethodstatus') }}</span>
                            
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        
                    </div>

                    <div class="col-md-12 mt-3">
                        <button class="btn bg_own_color text-light"
                                            wire:click="refundOrder({{ $orderId, $payment->transactionCode}})"
                                            wire:loading.attr="disabled">{{ trans('backend.update') }}</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="$set('showModalRefund', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>