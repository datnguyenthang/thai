<div>
    <h1>{{ trans('backend.createpaymentmethod') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="paymentmethodid" value="{{ $paymentmethodId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.paymentmethodname') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.paymentmethoddescription') }}</label>
            <input type="text" class="form-control w-50" wire:model="description">
            @error('description') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.paymentmethodistransaction') }}</label>
            <input type="checkbox" class="form-control w-50" wire:model="isTransaction">
            @error('isTransaction') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.paymentmethodstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(PAYMENTMETHODSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
