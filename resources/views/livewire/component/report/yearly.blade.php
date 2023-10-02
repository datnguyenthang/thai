<div>
    <script type="module" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="row  mt-5">
        <div class="col-xl-6 col-lg-6">
            @include('livewire.component.report.chart.revenuechart')
        </div>
        <div class="col-xl-6 col-lg-6">
            @include('livewire.component.report.chart.orderchart')
        </div>
    </div>

    @include('livewire.component.report.view.filterlist')

    @include('livewire.component.report.view.ridelist')

    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;"></div>
    </div>
</div>
