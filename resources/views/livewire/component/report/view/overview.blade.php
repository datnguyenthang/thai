<div class="row">
    <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                    <div>
                        <h3 class="text-success">฿{{ number_format($revenueOrderIndex, 0) }}</h3>
                        <p class="mb-0">REVENEU NEW ORDER</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-rocket text-success fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                    <div>
                        <h3 class="text-danger">฿{{ number_format($revenueRideIndex, 0) }}</h3>
                        <p class="mb-0">REVENEU TRAVELED</p>
                    </div>
                    <div class="align-self-center">
                        <i class="far fa-user  text-danger fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                    <div>
                        <h3 class="text-warning">{{ $paxOrderIndex }}</h3>
                        <p class="mb-0">TOTAL PAX ORDER</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-pie text-warning fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                    <div>
                        <h3 class="text-info">{{ $paxTravelIndex }}</h3>
                        <p class="mb-0">TOTAL PAX TRAVELED</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-map-signs text-info fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>