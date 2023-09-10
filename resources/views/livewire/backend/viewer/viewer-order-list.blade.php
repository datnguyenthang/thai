<div>
    <h1>{{ trans('backend.listorder') }}</h1>

    @include('livewire.backend.viewer.orderlist.filter')

    @include('livewire.backend.viewer.orderlist.data')

    @include('livewire.backend.viewer.orderlist.modalview')

    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;"></div>
    </div>
</div>