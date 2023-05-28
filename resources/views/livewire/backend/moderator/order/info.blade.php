<div class="row align-items-start d-flex">
    <div class="col-md-12 col-md-offset-1">
        <div class="border rounded-3 overflow-hidden p-4 mt-3">
            <h2 class="select-departure-header mb-3" >{{ trans('backend.contactdetail') }}</h2>
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="firstName" placeholder="{{ trans('backend.firstname') }}" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="lastName" placeholder="{{ trans('backend.lastname') }}" required />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="email" placeholder="{{ trans('backend.email') }}" required />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="phone" placeholder="{{ trans('backend.phone') }}" required />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="pickup" placeholder="{{ trans('backend.pickup') }}"  />
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="dropoff" placeholder="{{ trans('backend.dropoff') }}" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <textarea class="form-control" wire:model="note" rows="4" placeholder="{{ trans('backend.note') }}"></textarea>
                </div>
            </div>
        </div>

        <div class="border rounded-3 overflow-hidden p-4 mt-3">
            
            <hr />

            <div class="row mt-3">
                <div class="col-md-6">
                    <button class="btn btn-link text-info" type="button">{{ trans('backend.inputcoupon') }}</button>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" wire:model="coupon" placeholder="{{ trans('backend.coupon') }}" />
                    <button class="btn btn-success float-end mt-2" type="button">{{ trans('backend.apply') }}</button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" wire:click="bookTicket" class="btn btn-success form-control">
                        {{ trans('backend.review') }}
                    </button>
                </div>
            </div>
        </div> 
    </div>
</div>