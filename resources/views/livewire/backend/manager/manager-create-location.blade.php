<div>
    <h1>Create location</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="locationid" value="{{ $locationId }}">

        <div class="form-outline mb-4">
            <label class="form-label">Name:</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-outline mb-4">
            <label class="form-label">Status:</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(LOCATIONSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
