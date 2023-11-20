<div>

    @include('livewire.utils.rides.indexDaily')

    @include('livewire.utils.rides.filterDashboard')

    @include('livewire.utils.rides.rideList')

    @include('livewire.utils.rides.detailRide')

    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;">
    </div>
</div>
