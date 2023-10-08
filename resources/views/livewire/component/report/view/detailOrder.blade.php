<div class="row mt-2">
    <div class="col-xl-4 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                    <div>
                        <p class="mb-0">CONFIRM</p>
                        <h3 class="text-danger">{{ $confirmOrderTotal }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-double text-success fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                    <div>
                        <p class="mb-0">RESERVE</p>
                        <h3 class="text-success">{{ $reserveOrderTotal }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line text-info fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between px-md-1">
                    <div>
                        <p class="mb-0">CANCEL</p>
                        <h3 class="text-info">{{ $cancelOrderTotal }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-ban text-danger fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>