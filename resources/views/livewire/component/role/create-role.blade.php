<div>
    <h1>{{ trans('backend.createrole') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="roleid" value="{{ $roleId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.username') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.userstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(USERSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
