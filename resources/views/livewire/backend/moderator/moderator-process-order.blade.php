<div>
    {{-- Breadcrumbs::render('processpayment') --}}

    @include('livewire.backend.moderator.processorder.booking-detail')

    {{-- SHOW IMAGES BANK TRANSFER FOR ONLINE CUSTOMER --}}
    @if (!empty($photos))
        @include('livewire.backend.moderator.processorder.banktransfer-image-detail')
    @endif

    {{-- SHOW PROCESS PAYMENT INFORMATION --}}
    @include('livewire.backend.moderator.processorder.process-payment')

    {{-- PROCESS ORDER--}}
    @include('livewire.backend.moderator.processorder.process-order')

    {{-- UPDATE ORDER --}}
    @if ($showModalUpdateOrder)
        @livewire('backend.moderator.processorder.update-order', ['orderId' => $orderId, 'pickup' => $order->pickup, 'dropoff' => $order->dropoff])
    @endif
    <!-- Loading Overlay, show loading to lock user action-->
    @include('loading.loading')
    
    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModalStatus === true || $showModalPayment === true || $showModalUpdateOrder === true) block @else none @endif;"></div>
    </div>

</div>
