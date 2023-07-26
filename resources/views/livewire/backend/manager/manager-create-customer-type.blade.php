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
            <input type="number" class="form-control w-50" wire:model="price" min="0" {{ $type == ONLINEPRICE ? 'disabled' : '' }}>
            @error('phone') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mt-3 mb-4">
            <label class="form-label">{{ trans('backend.customertypetype') }}</label>
            <select id="type" class="form-select w-50" wire:model="type">
                @foreach(AGENTLOCAL as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-outline mt-3 mb-4">
            <label class="form-label">{{ trans('backend.customertypestatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(CUSTOMERTYPESTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
