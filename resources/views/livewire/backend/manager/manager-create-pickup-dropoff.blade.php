<div>
    {{-- Be like water. --}}
    <h1>{{ trans('backend.createpkdp') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="pkdpId" value="{{ $pkdpId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.pkdpname') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-outline mt-3 mb-4">
            <label class="form-label">{{ trans('backend.locationstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(PICKUPDROPOFFSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button wire:loading.attr="disabled" wire:click="save" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
