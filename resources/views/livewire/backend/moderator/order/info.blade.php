<div class="row align-items-start d-flex">
    <div class="col-md-12 col-md-offset-1">
        <div class="border rounded-3 overflow-hidden p-4 mt-3">
            <h2 class="select-departure-header mb-3" >{{ trans('messages.contactdetail') }}</h2>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="firstName" placeholder="{{ trans('messages.firstname') }}" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="lastName" placeholder="{{ trans('messages.lastname') }}" required />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="email" placeholder="{{ trans('messages.email') }}" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="phone" placeholder="{{ trans('messages.phone') }}" required />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="pickup" placeholder="{{ trans('messages.pickup') }}"  />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="dropoff" placeholder="{{ trans('messages.dropoff') }}" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <textarea class="form-control" wire:model="note" rows="4" placeholder="{{ trans('messages.note') }}"></textarea>
                </div>
            </div>
        </div>

        <div class="border rounded-3 overflow-hidden p-4 mt-3">
            
            <hr />

            <div class="row mt-3">
                <div class="col-md-6">
                    <button class="btn btn-link text-info" type="button">{{ trans('messages.inputcoupon') }}</button>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="coupon" placeholder="{{ trans('messages.coupon') }}" />
                    <button class="btn btn-success float-end mt-2" type="button">{{ trans('messages.apply') }}</button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" wire:click="bookTicket" class="btn btn-success form-control">
                        {{ trans('messages.review') }}
                    </button>
                </div>
            </div>
        </div> 
    </div>
</div>