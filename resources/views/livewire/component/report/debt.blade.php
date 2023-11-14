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
            @if (!empty($agentDebts))
                <table class="table table-striped datatable-table table-secondary mt-3">
                    <thead>
                        <tr>
                            <th>Agent Name</th>
                            <th>Agent Code</th>
                            <th>Revenue</th>
                            <th>Paid</th>
                            <th>Not Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agentDebts as $agentDebt)
                            <tr>
                                <td>{{ $agentDebt->agentName }}</td>
                                <td>{{ $agentDebt->agentCode }}</td>
                                <td>฿{{ number_format($agentDebt->revenue, 0) }}</td>
                                <td class="text-success">฿{{ number_format($agentDebt->paid, 0) }}</td>
                                <td class="text-danger">฿{{ number_format($agentDebt->notpaid, 0) }}</td>
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
