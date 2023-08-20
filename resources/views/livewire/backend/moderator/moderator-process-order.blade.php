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

    <!-- Loading Overlay, show loading to lock user action-->
    @include('livewire.frontend.homepage.payment.loading')
    
    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModalStatus === true || $showModalPayment === true) block @else none @endif;"></div>
    </div>

</div>
