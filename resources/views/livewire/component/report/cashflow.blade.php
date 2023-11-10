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
                            @foreach($headerTables as $key => $headerTable)
                                <th>{{ ucwords($headerTable) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cashflows as $cashflow)
                            <tr>
                                @foreach($headerTables as $headerTable)
                                    @php
                                        $value = $cashflow->{$headerTable};
                                        $hasDecimal = strpos($value, '.') !== false && preg_match('/\.\d+/', $value);
                                        $formattedValue = $hasDecimal ? '฿'.number_format($value, 0) : $value;
                                    @endphp
                            
                                    <td>{{ $formattedValue }}</td>
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
