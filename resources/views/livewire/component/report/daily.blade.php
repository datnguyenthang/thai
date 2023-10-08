<div>
    @include('livewire.component.report.view.overview')

    <script type="module" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="row mt-2">
        <div class="col-xl-6 col-lg-6">
            @include('livewire.component.report.chart.revenueChart')
        </div>
        <div class="col-xl-6 col-lg-6">
            @include('livewire.component.report.chart.paxChart')
        </div>
    </div>

    @include('livewire.component.report.view.filterList')

    @include('livewire.component.report.view.detailOrder')

    @include('livewire.component.report.view.detailCustomerType')

    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;"></div>
    </div>
</div>
