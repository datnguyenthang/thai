<div>
    {{-- Breadcrumbs::render('processpayment') --}}

    @include('livewire.backend.moderator.processorder.booking-detail')

    @if($order->paymentMethod == BANKTRANSFER && !$order->userId)
        @include('livewire.backend.moderator.processorder.banktransfer-detail')
    @endif

    {{-- Only process order by image when custers has been uploaded images and people don't --}}
    @if($order->status == UPPLOADTRANSFER) 
        @include('livewire.backend.moderator.processorder.process-payment')
    @endif

    @if($order->status == CONFIRMEDORDER || $order->status == CANCELDORDER)
        @include('livewire.backend.moderator.processorder.payment-detail')
    @endif
</div>
