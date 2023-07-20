<div>
    <h1>{{ trans('backend.createmenu') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="menuid" value="{{ $menuId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.menuname') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.menuurl') }}</label>
            <input type="text" class="form-control w-50" wire:model="url">
            @error('url') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.parentname') }}</label>
            <select id="parent_id" class="form-select w-50" wire:model="parent_id">
                <option value=""></option>
                @foreach($menuList as $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>
            @error('parent_id') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-outline mt-3 mb-4">
            <label class="form-label">{{ trans('backend.locationstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(MENUSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button wire:loading.attr="disabled" wire:click="save" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
