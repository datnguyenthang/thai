<div>
    <h1>{{ trans('backend.createlocation') }}Create location</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="locationid" value="{{ $locationId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.locationname') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.locationstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(LOCATIONSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
