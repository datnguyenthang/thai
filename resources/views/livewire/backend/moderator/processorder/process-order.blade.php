<div class="border rounded-3 overflow-hidden p-4 mt-3 mb-4">
    <h4 class="select-departure-header mb-3" >{{ trans('backend.orderstatus') }}</h4>
    <div class="col-lg-12 mx-auto">
        <!-- Bank transfer info -->
        <div class="tab-pane fade show active pt-3">
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>{{ trans('backend.orderstatus') }}</th>
                        <th>{{ trans('backend.note') }}</th>
                        <th>{{ trans('backend.changeDate') }}</th>
                        <th>{{ trans('backend.username') }}</th>
                        <th>{{ trans('backend.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($orderStatuses))
                        @foreach ($orderStatuses as $status)
                            <tr>
                                <th>{{ ORDERSTATUS[$status->status] }}</th>
                                <th>{{ $status->note }}</th>
                                <th>{{ $status->changeDate }}</th>
                                <th>{{ $status->name }}</th>
                                <th></th>
                            </tr>
                        @endforeach
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>
                                    <button class="btn btn bg_own_color text-light" 
                                            wire:click="viewOrderStatus()">{{ trans('backend.updateorderstatus') }}</button></th>
                            </tr>
                    @endif
                <tbody>
            </table>
        </div>
        <!-- End -->
    </div>
</div>

{{--Show modal boostrap to quick update order status--}}
<div class="modal fade show" tabindex="-1" 
style="display: @if($showModalStatus === true) block @else none @endif;" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('backend.updateorderstatus') }}</h5>
                <button class="btn-close" wire:click="$set('showModalStatus', false)"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="form-label">{{ trans('backend.orderstatus') }}</span>
                            <select id="status" name="status" class="form-select" wire:model="status">
                                @foreach(ORDERSTATUS as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="form-label">{{ trans('backend.note') }}</span>
                            <textarea id="note" name="note" class="form-control" type="date" wire:model="note" cols="50" rows="5"></textarea>
                            @error('note') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button class="btn bg_own_color text-light"
                                            wire:click="updateOrderStatus()"
                                            wire:loading.attr="disabled">{{ trans('backend.updateorderstatus') }}</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="$set('showModalStatus', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>