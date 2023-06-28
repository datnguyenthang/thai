<div>
    <h1>{{ trans('backend.createpromotion') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="promotionid" value="{{ $promotionId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.promotionname') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.promotioncode') }}</label>
            <input type="text" class="form-control w-50" wire:model="code">
            @error('code') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.promotionquantity') }}</label>
            <input type="text" class="form-control w-50" wire:model="quantity">
            @error('quantity') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.promotiondiscount') }}</label>

            <select id="discount" class="form-select w-50" wire:model="discount">
                @for ($i = 0; $i <= 100; $i++)
                    <option value="{{ $i/100 }}">{{ $i }}</option>
                @endfor
            </select>
            @error('discount') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.startdate') }}</label>
            <input type="date" class="form-control w-50" wire:model="fromDate">
            @error('fromDate') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.todate') }}</label>
            <input type="date" class="form-control w-50" wire:model="toDate">
            @error('toDate') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.locationstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(LOCATIONSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
