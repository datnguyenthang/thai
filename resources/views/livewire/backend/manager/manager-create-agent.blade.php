<div>
    <h1>{{ trans('backend.createagent') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="agentid" value="{{ $agentId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentname') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(LOCATIONSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
