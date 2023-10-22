<div>
    <script type="module" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    

    @include('livewire.component.report.performance.filter')

    @include('livewire.component.report.performance.dataChart')

    @include('livewire.component.report.performance.performanceChart')
    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</div>
