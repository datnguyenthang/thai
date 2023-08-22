<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <h1>{{ trans('backend.createpage') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="pageid" value="{{ $pageId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.pagename') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.pageslug') }}</label>
            <input type="text" class="form-control w-50" wire:model="slug">
            @error('slug') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <button wire:loading.attr="disabled" wire:click="save" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
