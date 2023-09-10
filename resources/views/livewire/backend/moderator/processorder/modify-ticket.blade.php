{{--Show modal boostrap to quick modify ticket--}}
@if($selectedTicket)
    <div class="modal fade show" tabindex="-1" 
    style="display: @if($showModalModifyTicket === true) block @else none @endif;" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ trans('backend.modifyticketorder') }}</h5>
                    <button class="btn-close" wire:click="$set('showModalModifyTicket', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.ordercode') }} : </span>
                            <span class="form-label fw-bold">{{ $selectedTicket->code }}</span>
                        </div>
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.ridename') }} : </span>
                            <span class="form-label fw-bold">{{ $selectedTicket->name }}</span>
                        </div>
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.seatclass') }} : </span>
                            <span class="form-label fw-bold">{{ $selectedTicket->seatClassName }}</span>
                        </div>
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.price') }} : </span>
                            <span class="form-label fw-bold">{{ round($selectedTicket->price) }}</span>
                        </div>
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.status') }} : </span>
                            <span class="form-label fw-bold">{{ TICKETSTATUS[$selectedTicket->status] }}</span>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <span class="form-label fw-bold">{{ trans('backend.ticketstatus') }}</span>
                                <select id="ticketStatus" name="ticketStatus" class="form-select" wire:model="ticketStatus">
                                    @foreach(TICKETSTATUS as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('ticketStatus') <span class="text-danger error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <button class="btn bg_own_color text-light"
                                                wire:click="updateTicket()"
                                                wire:loading.attr="disabled">{{ trans('backend.update') }}</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="$set('showModalModifyTicket', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif