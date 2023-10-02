<div>
    <h1>{{ trans('backend.saleperformance') }}</h1>
    <table class="table table-striped mt-3">
        <thead>
            <tr class="table-primary">
                <th>{{ trans('backend.saleman') }}</th>
                <th>{{ trans('backend.agent') }}</th>
                <th>{{ trans('backend.saletotalprice') }}</th>
                <th>{{ trans('backend.revenue') }}</th>
                <th>{{ trans('backend.totalorder') }}</th>
                <th>{{ trans('backend.achieve') }}</th>

            </tr>
        </thead>
        <tbody>
            @if (!empty($performances))
                @foreach ($performances as $performance)
                    <tr>
                        <td>{{ $performance->userName ?? "ONLINE" }}</td>
                        <td>{{ $performance->agentName ?? "" }}</td>
                        <td>{{ round($performance->totalPrice) }}</td>
                        <td>Revenue</td>
                        <td>{{ $performance->totalOrder }}</td>
                        <td>Achieve</td>
                        
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center text-danger" colspan="10">{{ trans('backend.noorderfound') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
