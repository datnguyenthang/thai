<div>
    <h1>{{ trans('backend.listride') }}</h1>
    <div class="row">
        <div class="col-md-2">
            <a href="/ride/create/0" class="btn btn-info">{{ trans('backend.createride') }}</a>
        </div>
        <div class="col-md-2">
            <a href="/ride/massiveCreate" class="btn btn-success">{{ trans('backend.createmassiveride') }}</a>
        </div>
    </div>

    @include('livewire.backend.manager.ride.filter')

    @include('livewire.backend.manager.ride.data')

    @include('livewire.backend.manager.ride.detail-ride')

    {{--Show modal boostrap to detail ride--}}
    <div class="modal fade show" tabindex="-1" 
        style="display: @if($showModalMassEdit === true) block @else none @endif;" role="dialog" wire:model="editMassRide">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('backend.edittripridelist') }}</h5>
                    <button wire:click="$set('showModalMassEdit', false)">{{ trans('backend.close') }}</button>
                </div>
                <div class="modal-body">
                    @if(!empty($selected) && $showModalMassEdit)
                        @livewire('backend.manager.ride.edit-massive-ride', ['selected'=> $selected])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true || $showModalMassEdit == true) block @else none @endif;"></div>
    </div>
</div>