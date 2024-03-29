<div>
    <h1>{{ trans('backend.listorder') }}</h1>

    @include('livewire.backend.moderator.orderlist.filter')

    @include('livewire.backend.moderator.orderlist.data')

    @include('livewire.backend.moderator.orderlist.modalview')

    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;"></div>
    </div>
</div>
