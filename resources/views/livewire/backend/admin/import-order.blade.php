<div>
    <h1>Import Order</h1>

    <form wire:submit.prevent="import">
        <input type="file" wire:model="file">
        @error('file') <span class="text-red-500">{{ $message }}</span> @enderror

        <button class="btn btn-success" type="submit">Import</button>
    </form>
</div>
