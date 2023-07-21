<div>
    {{-- Do your work, then step back. --}}
    {{ Breadcrumbs::render('contactus') }}
    {!! trans('home.contactus') !!}

    @if($step == 1)
        <h4 class="d-flex align-items-center justify-content-center mt-5 mb-3">{{ trans('messages.contactformtitle') }}</h4>
        
        <form wire:submit.prevent="sendMessage">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact_name" class="form-label">{{ trans('messages.contactname') }}</label>
                        <input type="text" id="contact_name" wire:model="name" name="name" class="form-control">
                        @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact_email" class="form-label">{{ trans('messages.contactemail') }}</label>
                        <input type="email" id="contact_email" wire:model="email" name="email" class="form-control">
                        @error('email') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">{{ trans('messages.contactmessage') }}</label>
                <textarea id="message" rows="10" wire:model="message" name="message" class="form-control"></textarea>
                @error('message') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-3">
                <input type="submit" value="Submit" id="submit-contact" wire:loading.attr="disabled" class="btn btn-lg bg_own_color text-light">
            </div>
        </form>
    @endif
    @if($step == 2)
        <div class="border rounded-3 overflow-hidden p-4 mt-3">
            <div class="row">
                <div class="col-md-4 text-center">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" class="own_color" width="75" height="75"
                            fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                        </svg>
                    </h2>
                </div>

                <div class="col-md-8 text-center pt-4">
                    <h5>{{ trans('messages.thankyoucontactmessage') }}</h5>
                </div>
            </div>
        </div>
    @endif
</div>
