<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>{{ trans('backend.saleman') }}</th>
                            <th>{{ trans('backend.agent') }}</th>
                            <th>{{ trans('backend.revenue') }}</th>
                            <th>{{ trans('backend.totalorder') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($pfmUsers))
                            @foreach ($pfmUsers as $key => $pfm)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $pfm->userName ?? "ONLINE" }}</td>
                                    <td>{{ $pfm->agentName ?? "" }}</td>
                                    <td nowrap>
                                        <span>{{ round($pfm->totalPrice) }}</span><br/>
                                        @if(isset($pfm->cpTotalPrice))
                                            <span class="text-{{ $pfm->cpTotalPrice ? 'success' : 'danger' }}">
                                                <i class="fas fa-caret-{{ $pfm->cpTotalPrice ? 'up' : 'down' }} me-1"></i><span>{{ $pfm->percentTotalPrice }}%</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td nowrap>
                                        <span>{{ $pfm->totalOrder }}</span><br/>
                                        @if(isset($pfm->cpTotalOrder))
                                            <span class="text-{{ $pfm->cpTotalOrder ? 'success' : 'danger' }}">
                                                <i class="fas fa-caret-{{ $pfm->cpTotalOrder ? 'up' : 'down' }} me-1"></i><span>{{ $pfm->percentTotalOrder }}%</span>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn bg_own_color" wire:key="{{ $pfm->id }}" wire:click="selectUser({{ $pfm->id }})"><i class="fas fa-chart-bar"></i></button>
                                    </td>
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
        </div>
    </div>
    <div class="col-6">
        <h5>{{ $userSelected ? $userSelected->name : 'General' }}</h5>
        <div class="card">
            <div class="card-body">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>