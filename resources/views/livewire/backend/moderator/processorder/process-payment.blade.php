
<div class="border rounded-3 overflow-hidden p-4 mt-3 mb-4">
    <h4 class="select-departure-header mb-3" >{{ trans('backend.review') }}</h4>
    <div class="col-lg-12 mx-auto">
        <div class="row">
            <div class="col-lg-1 col-sm-2">
            <div class="rdio rdio-primary">
                <input type="radio" class="" id="approval" value="{{ CONFIRMEDORDER }}" name="approval" wire:model="confirmation">
                <label for="approval" class="">{{ trans('backend.approval') }}</label>
            </div>
            </div>
            <div class="col-lg-1 col-sm-2">
            <div class="rdio rdio-primary">
                <input type="radio" class="" id="decline" value="{{ DECLINEDORDER }}" name="decline" wire:model="confirmation">
                <label for="decline" class="">{{ trans('backend.decline') }}</label>
            </div>
            </div>
        @if($confirmation == DECLINEDORDER)
            <div class="col-lg-8 col-sm-8">
                <label for="approval" class="">{{ trans('backend.reasondecline') }}</label>
                @error('reasonDecline') <span class="text-danger error">{{ $message }}</span> @enderror
                <textarea name="reasonDecline" cols="60" rows="5" wire:model="reasonDecline"></textarea>
            </div>
        @endif
            <div class="col-lg-2 col-sm-2">
                <button type="submit" wire:click="save" class="btn text-light bg_own_color">{{ trans('backend.save') }}</button>
            </div>
    </div>
</div>