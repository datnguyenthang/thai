<div class="page-title-box">
    <div class="row mt-3">
        <div class="col-4" wire:ignore>
            <button id="daterange" class="selectbox">
                <i class="fas fa-calendar"></i>
                <span></span>  <b class="caret"></b>
            </button>
        </div>
        <div class="col-4">
            <button id="comparedaterange" class="selectbox">
                <i class="fas fa-calendar"></i>
                <span>{{ $cpFromDate ? 'Compare: '.date('Y-m-d', strtotime($cpFromDate)).' - '.date('Y-m-d', strtotime($cpToDate)).'' : 'Compare: No compare!' }}</span>  <b class="caret"></b>
            </button>
        </div>

        <div class="col-3 align-self-center">
            <h3><strong>{{ trans('backend.saleperformance') }}</strong></h3>
        </div>
    </div>
</div>