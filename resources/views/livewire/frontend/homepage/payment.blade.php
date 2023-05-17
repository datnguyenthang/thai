<div>
    <form wire:submit.prevent="payment">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto m-3 p-2 border rounded-2">
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">{{ trans('messages.cardnumber') }}</label>
                        <input type="text" class="form-control" wire:model="cardNumber" placeholder="{{ trans('messages.enteryourcardnumber') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cardHolder" class="form-label">{{ trans('messages.cardholdername') }}</label>
                        <input type="text" class="form-control" wire:model="cardHolder" placeholder="{{ trans('messages.entercardholdername') }}" required>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="expirationDate" class="form-label">{{ trans('messages.expirationdate') }}</label>
                            <input type="text" class="form-control" wire:model="expirationDate" placeholder="MM/YY" required>
                        </div>
                        <div class="col">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" wire:model="cvv" placeholder="{{ trans('messages.entercvvcode') }}" required>
                        </div>
                    </div>
                    <div class=" text-center">
                        <button type="submit" class="btn btn-primary mt-3">{{ trans('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
