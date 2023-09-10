<div>
    <div class="container">
        <h1>{{ trans('backend.createorder') }}</h1>
    </div>
    <!-- STEPPER
        Allows a user to complete steps in a given process.
            * Required base class: .stepper
            @param .active
            @param .completed
            @param .disabled    
    -->
    <div class="stepper">
        <ul class="nav nav-tabs border-0" role="tablist">
            <li role="presentation" class="@if($step === 1) active @endif
                                           @if($step > 1) completed @endif" 
                wire:click="$set('step', 1)"
            >
                <a class="persistant-disabled" href="#" data-bs-toggle="tab" aria-controls="stepper-step-1" role="tab" title="Step 1">
                <span class="round-tab">{{ trans('backend.search') }}</span>
                </a>
            </li>

            <li role="presentation" class="@if($step === 2) active @endif
                                            @if($step > 2) completed @endif
                                            @if($step < 2) disabled @endif"
            >
                <a class="persistant-disabled" href="#" data-bs-toggle="tab" aria-controls="stepper-step-2" role="tab" title="Step 2">
                <span class="round-tab">{{ trans('backend.trip') }}</span>
                </a>
            </li>

            <li role="presentation" class="@if($step === 3) active @endif
                                            @if($step > 3) completed @endif
                                            @if($step < 3) disabled @endif"
            >
                <a class="persistant-disabled" href="#" data-bs-toggle="tab" aria-controls="stepper-step-3" role="tab" title="Step 3">
                <span class="round-tab">{{ trans('backend.info') }}</span>
                </a>
            </li>

            <li role="presentation" class="@if($step === 4) active @endif
                                            @if($step > 4) completed @endif
                                            @if($step < 4) disabled @endif"
            >
                <a class="persistant-disabled" href="#" data-bs-toggle="tab" aria-controls="stepper-step-4" role="tab" title="Complete">
                <span class="round-tab">{{ trans('backend.review') }}</span>
                </a>
            </li>

            <li role="presentation" class="@if($step === 5) active @endif
                                            @if($step >= 5) completed @endif
                                            @if($step < 5) disabled @endif"
            >
                <a class="persistant-disabled" href="#" data-bs-toggle="tab" aria-controls="stepper-step-5" role="tab" title="Complete">
                    <span class="round-tab">{{ trans('backend.done') }}</span>
                </a>
            </li>
        </ul>

        <!--TAB CONTENT-->
        <div class="tab-content">

            <div class="tab-pane p-1 fade @if($step === 1) in show active @endif" role="tabpanel" id="stepper-step-1">
                @include('livewire.backend.moderator.order.filter')
            </div>

            <div class="tab-pane p-1 fade @if($step === 2) in show active @endif" role="tabpanel" id="stepper-step-2">
                @include('livewire.backend.moderator.order.trip')
                <div class="container">
                    <div class="list-inline pull-left">
                        <a wire:click="$emit('refreshOrder')" class="btn bg-dark text-light" onclick="return confirm('Are you sure?');">Cancel</a>
                    </div>
                    <div class="list-inline pull-right">
                        <button class="btn bg_own_color text-light" 
                            wire:click="$set('step', {{ $step - 1 }})"
                            wire:loading.attr="disabled">{{ trans('backend.back') }}</button>

                        <button class="btn bg_own_color text-light @if($countTicketSelected === 0) disabled @endif" 
                            wire:click="$set('step', {{ $step + 1 }})"
                            wire:loading.attr="disabled">{{ trans('backend.next') }}</button>
                    </div>
                </div>
            </div>

            <div class="tab-pane p-1 fade @if($step === 3) in show active @endif" role="tabpanel" id="stepper-step-3">
                @include('livewire.backend.moderator.order.info')
                <div class="container">
                    <div class="list-inline pull-left">
                        <a wire:click="$emit('refreshOrder')" class="btn bg-dark text-light" onclick="return confirm('Are you sure?');">Cancel</a>
                    </div>
                    <div class="list-inline pull-right">
                        <button class="btn bg_own_color text-light" 
                            wire:click="$set('step', {{ $step - 1 }})"
                            wire:loading.attr="disabled">{{ trans('backend.back') }}</button>

                        <button class="btn bg_own_color text-light" 
                            wire:click="checkInfo"
                            wire:loading.attr="disabled">{{ trans('backend.next') }}</button>
                    </div>
                </div>
            </div>

            <div class="tab-pane p-1 fade @if($step === 4) in show active @endif" role="tabpanel" id="stepper-step-4">
                @include('livewire.backend.moderator.order.review')
                <div class="container">
                    <div class="list-inline pull-left">
                        <a wire:click="$emit('refreshOrder')" class="btn bg-dark text-light" onclick="return confirm('Are you sure?');">Cancel</a>
                    </div>
                    <div class="list-inline pull-right">
                        <button class="btn bg_own_color text-light"
                            wire:click="$set('step', {{ $step - 1 }})"
                            wire:loading.attr="disabled">{{ trans('backend.back') }}</button>

                        <button class="btn bg_own_color text-light" 
                            wire:click="bookTicket"
                            wire:loading.attr="disabled">{{ trans('backend.booking') }}</button>
                    </div>
                </div>
                <!-- Loading Overlay, show loading to lock user action-->
                @include('loading.loading')
            </div>

            <div class="tab-pane p-1 fade @if($step === 5) in show active @endif" role="tabpanel" id="stepper-step-5">
                @if($step === 5) @include('livewire.backend.moderator.order.done') @endif
                <div class="container">
                </div>
            </div>

        </div>
    </div>

</div>
