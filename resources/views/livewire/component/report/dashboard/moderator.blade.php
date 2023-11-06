<div>
    <!-- Content Row -->
    <div class="row">
        <!-- List Rides -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                @include('livewire.component.report.dashboard.utils.filter')

                @include('livewire.component.report.dashboard.utils.listRide')
            </div>
        </div>
    </div>

    @include('livewire.component.report.dashboard.utils.listPassenger')

    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="module">

    </script>
</div>