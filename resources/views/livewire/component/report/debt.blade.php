<div>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between row bg-success text-dark bg-opacity-25">
        <div class="col-md-4">
            <label>From</label>
            <input id="fromDate" name="fromDate" wire:model="fromDate" class="form-control" type="{{ $selectType }}">
        </div>
        <div class="col-md-4">
            <label>To</label>
            <input id="toDate" name="toDate" wire:model="toDate" class="form-control" type="{{ $selectType }}">
        </div>
        {{--
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-control-label"></label>
                <button class="btn bg_own_color text-light form-control" wire:click="downloadCashflow">Download</button>
            </div>
        </div>
        --}}
    </div>

    <!-- Card Body -->
    <div class="card-body" style="height: 30rem;">
        <div class="row">
            @if (!empty($agentDebts))
                <table class="table table-striped datatable-table table-secondary mt-3">
                    <thead>
                        <tr>
                            <th rowspan="1">Agent Name</th>
                            <th rowspan="1">Agent Code</th>
                            <th colspan="3" class="table-success text-center">Ordered</th>
                            <th colspan="3" class="table-danger text-center">Traveled</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th class="table-success">Pax</th>
                            <th class="table-success">Paid</th>
                            <th class="table-success">Not Paid</th>
                            <th class="table-danger">Pax</th>
                            <th class="table-danger">Paid</th>
                            <th class="table-danger">Not Paid</th>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">Total</th>
                            <th class="table-success">{{ $agentDebts->sum('pax') }}</th>
                            <th class="table-success">฿{{ number_format($agentDebts->sum('paid'), 0) }}</th>
                            <th class="table-success">฿{{ number_format($agentDebts->sum('notpaid'), 0) }}</th>
                            <th class="table-danger">{{ $agentDebts->sum('ridePax') }}</th>
                            <th class="table-danger">฿{{ number_format($agentDebts->sum('ridePaid'), 0) }}</th>
                            <th class="table-danger">฿{{ number_format($agentDebts->sum('rideNotPaid'), 0) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agentDebts as $agentDebt)
                            <tr>
                                <td>{{ $agentDebt->agentName }}</td>
                                <td>{{ $agentDebt->agentCode }}</td>
                                <td class="table-success">{{ $agentDebt->pax }}</td>
                                <td class="table-success">฿{{ number_format($agentDebt->paid, 0) }}</td>
                                <td class="table-success">฿{{ number_format($agentDebt->notpaid, 0) }}</td>
                                <td class="table-danger">฿{{ number_format($agentDebt->ridePax, 0) }}</td>
                                <td class="table-danger">฿{{ number_format($agentDebt->ridePaid, 0) }}</td>
                                <td class="table-danger">฿{{ number_format($agentDebt->rideNotPaid, 0) }}</td>
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
