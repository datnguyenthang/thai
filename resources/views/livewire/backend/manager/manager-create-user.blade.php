<div>
    <h1>{{ trans('backend.createuser') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="userid" value="{{ $userId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.username') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.useremail') }}</label>
            <input type="email" class="form-control w-50" wire:model="email">
            @error('email') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label  class="form-label">{{ trans('backend.userpassword') }}</label>
            <input type="password" class="form-control w-50" wire:model="password">
            @error('password') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.userrole') }}</label>
            <select id="role" class="form-select w-50" wire:model="role">
                @foreach([3 => 'Moderator', 2 => 'Manager'] as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.userstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(USERSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
