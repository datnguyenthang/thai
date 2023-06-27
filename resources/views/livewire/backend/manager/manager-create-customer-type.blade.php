<div>
    <h1>{{ trans('backend.createcustomertype') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="customerTypeId" value="{{ $customerTypeId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.customertypename') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.customertypecode') }}</label>
            <input type="text" class="form-control w-50" wire:model="code">
            @error('code') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.customertypeprice') }}</label>
            <input type="number" class="form-control w-50" wire:model="price" min="0">
            @error('phone') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
