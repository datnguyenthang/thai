{{--Show modal boostrap to detail ride--}}
<div class="modal fade show" tabindex="-1" 
style="display: @if($showModal === true) block @else none @endif;" role="dialog" wire:model="viewRide">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('backend.ridedetail') }}</h5>
                <button wire:click="$set('showModal', false)">{{ trans('backend.close') }}</button>
            </div>
            <div class="modal-body">
                @if($showModal === true &&  $rideDetail)
                    <p>{{ trans('backend.id') }} : {{ $rideDetail['id'] }}</p>
                    <p>{{ trans('backend.ridename') }} : {{ $rideDetail['name'] }}</p>
                    <p>{{ trans('backend.fromlocation') }} : {{ $rideDetail['fromLocation'] }}</p>
                    <p>{{ trans('backend.tolocation') }} : {{ $rideDetail['toLocation'] }}</p>
                    <p>{{ trans('backend.departtime') }} : {{ $rideDetail['departTime'] }}</p>
                    <p>{{ trans('backend.returntime') }}: {{ $rideDetail['returnTime'] }}</p>
                    <p>{{ trans('backend.departdate') }}: {{ $rideDetail['departDate'] }}</p>
                    <p>{{ trans('backend.colorcode') }}: {{ $rideDetail['colorCode'] }}</p>
                    <p>{{ trans('backend.ridestatus') }} : {{ RIDESTATUS[$rideDetail['status']] }}</p>
                    <!-- Other details -->

                    <table class="table">
                        <thead>
                            <tr>
                            <th>{{ trans('backend.seatclassname') }}</th>
                            <th>{{ trans('backend.capacity') }}</th>
                            <th>{{ trans('backend.price') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($seatClasses as $index => $seatClass)
                            <tr>
                                <td>{{ $seatClass['name']}}</td>
                                <td>{{ $seatClass['capacity']}}</td>
                                <td>{{ $seatClass['price']}}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>