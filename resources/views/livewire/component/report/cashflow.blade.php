<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between row bg-success text-dark bg-opacity-25">
        <div class="col-md-4">
            <label>From</label>
            <input id="fromDate" name="fromDate" wire:model="fromDate" class="form-control" type="{{ $selectType }}">
        </div>
        <div class="col-md-4">
            <label>To</label>
            <input id="toDate" name="toDate" wire:model="toDate" class="form-control" type="{{ $selectType }}">
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-control-label"></label>
                <button class="btn bg_own_color text-light form-control" wire:click="downloadCashflow">Download</button>
            </div>
        </div>
    </div>

    <!-- Card Body -->
    <div class="card-body" style="height: 30rem;">
        <div class="row">
            @if (!empty($cashflows))
                <table class="table table-striped datatable-table table-secondary mt-3">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="table-warning"></th>
                            <th class="table-warning"></th>
                            <th colspan="{{ $colPaid->count() + 1 }}" class="table-success text-center">PAID</th>
                            <th colspan="{{ $colNotPaid->count() + 1 }}" class="table-danger text-center">NOT PAID</th>
                        </tr>
                        <tr>
                            @foreach($headerTables as $key => $headerTable)
                                @php
                                    $class = "";

                                    if ($headerTable == 'revenue' || $headerTable == 'pax') $class = "table-warning";
                                    if (str_contains($headerTable, '-paid')) {
                                        $class = "table-success";
                                        $headerTable = str_replace('-paid', '', $headerTable);
                                    }
                                    if (str_contains($headerTable, '-notpaid')) {
                                        $class = "table-danger";
                                        $headerTable = str_replace('-notpaid', '', $headerTable);
                                    }
                                    if ($headerTable == 'paid') {
                                        $class = "table-success";
                                        $headerTable = 'Total';
                                    }
                                    if ($headerTable == 'notpaid') {
                                        $class = "table-danger";
                                        $headerTable = 'Total';
                                    }
                                @endphp
                                <th class="{{ $class }}">{{ ucwords($headerTable) }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th colspan="1" class="text-center">TOTAL</th>
                            @foreach($headerTables as $headerTable)
                                @php
                                    $class = "";

                                    //if ($headerTable == 'data' || $headerTable == 'revenue' || $headerTable == 'pax') continue;
                                    if ($headerTable == 'data') continue;
                                    
                                    $totals = $cashflows->sum($headerTable);

                                    if (str_contains($headerTable, '-paid')) {
                                        $class = "table-success";
                                        $headerTable = str_replace('-paid', '', $headerTable);
                                    }
                                    if (str_contains($headerTable, '-notpaid')) {
                                        $class = "table-danger";
                                        $headerTable = str_replace('-notpaid', '', $headerTable);
                                    }
                                    if ($headerTable == 'revenue' || $headerTable == 'pax') {
                                        $class = "table-warning";
                                    }

                                    if ($headerTable == 'paid') {
                                        $class = "table-success";
                                        $headerTable = 'Total';
                                    }
                                    if ($headerTable == 'notpaid') {
                                        $class = "table-danger";
                                        $headerTable = 'Total';
                                    }
                                @endphp

                                <th colspan="1" class="{{ $class }}">
                                    {{ $headerTable == 'pax' ? '฿'.number_format($totals, 0) : number_format($totals, 0) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cashflows as $cashflow)
                            <tr>
                                @foreach($headerTables as $headerTable)
                                    @php
                                        $class = "";

                                        if ($headerTable == 'revenue' || $headerTable == 'pax') $class = "table-warning";
                                        if (str_contains($headerTable, '-paid')) $class = "table-success";
                                        if (str_contains($headerTable, '-notpaid')) $class = "table-danger";
                                        if ($headerTable == 'paid') $class = "table-success";
                                        if ($headerTable == 'notpaid') $class = "table-danger";

                                        $value = $cashflow->{$headerTable};
                                        $hasDecimal = strpos($value, '.') !== false && preg_match('/\.\d+/', $value);
                                        $formattedValue = $hasDecimal ? '฿'.number_format($value, 0) : $value;
                                    @endphp
                            
                                    <td class="{{ $class }}">{{ $formattedValue }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div>
                    <td class="text-center text-danger" colspan="10">{{ trans('backend.nodatafound') }}</td>
                </div>
            @endif
        </div>
    </div>
</div>
