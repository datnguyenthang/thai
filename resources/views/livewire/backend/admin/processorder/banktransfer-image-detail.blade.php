<div class="border rounded-3 overflow-hidden p-4 mt-3">
    <!---PAYMENT--->
    <section class="">
        <h4 class="select-departure-header mb-3" >{{ trans('backend.paymentimages') }}</h4>
        <div class="col-lg-12 mx-auto">
            
            <!-- Bank transfer info -->
            <div id="bank-tranfer" class="tab-pane fade show active pt-3">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ trans('messages.file') }}</th>
                            <th>{{ trans('messages.filename') }}</th>
                            <th>{{ trans('messages.dimensions') }}</th>
                            <th>{{ trans('messages.extension') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($photos)
                            @foreach ($photos as $photo)
                                <tr>
                                    <th class="file">
                                        <a href={{ $photo['url'] }} target="_blank">
                                            <img src="{{ $photo['url'] }}" width="75" height="75" alt="Proof image" />
                                        </a>
                                    </th>
                                    <th class="filename">{{ $photo['name'] }}</th>
                                    <th class="dimensions">{{ $photo['dimension'] }}</th>
                                    <th class="extension">{{ $photo['extension'] }}</th>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>{{ trans('messages.nofile') }}</td>
                            </tr>
                        @endif
                    <tbody>
                </table>
            </div> <!-- End -->
        </div>
    </section>
</div>