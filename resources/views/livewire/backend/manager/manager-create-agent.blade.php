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
            <label class="form-label">{{ trans('backend.agentcode') }}</label>
            <input type="text" class="form-control w-50" wire:model="code">
            @error('code') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agenttype') }}</label>
            <select id="agentType" class="form-select w-50" wire:model="agentType" multiple>
                @foreach($customerType as $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>
            @error('agentType') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.atype') }}</label>
            <select id="type" class="form-select w-50" wire:model="type">
                @foreach(ATYPE as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @error('type') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentmanager') }}</label>
            <input type="text" class="form-control w-50" wire:model="manager">
            @error('manager') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentemail') }}</label>
            <input type="text" class="form-control w-50" wire:model="email">
            @error('email') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentphone') }}</label>
            <input type="tel" class="form-control w-50" oninput="this.value = this.value.replace(/[^0-9]/g, '')" wire:model="phone">
            @error('phone') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentline') }}</label>
            <input type="text" class="form-control w-50" wire:model="line">
            @error('line') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentpaymenttype') }}</label>
            <select id="paymentType" class="form-select w-50" wire:model="paymentType">
                @foreach(AGENTPAYMENTTYPE as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @error('paymentType') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
        
        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.agentstatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(AGENTSTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" wire:loading.attr="disabled" class="btn btn-success">{{ trans('backend.save') }}</button>
    </form>
</div>
