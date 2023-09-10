<div>
    @if (!$chargeId) 
        <form id="checkoutPromptpayForm" wire:submit.prevent="promptpay">
            <input type="hidden" name="omiseSource" wire:model.defer="source">
            <button type="submit" wire:loading.attr="disabled" class="form-control bg_own_color" wire:loading.attr="disabled" id="checkoutPromptpayButton">
                <span wire:loading.remove>Pay with Promptpay</span>
                <span wire:loading>Loading...</span>
            </button>
        </form>

        <script>
            var buttonPromptpay = document.querySelector("#checkoutPromptpayButton");
            var formPromptpay = document.querySelector("#checkoutPromptpayForm");

            buttonPromptpay.addEventListener("click", (event) => {
                event.preventDefault();

                Omise.setPublicKey("{{ $publicKey }}");
                Omise.createSource('promptpay', {
                    "amount": {{ $amount }},
                    "currency": "THB"
                }, function(statusCode, response) {
                    if (statusCode == '200'){
                        formPromptpay.omiseSource.value = response.id;
                        @this.set('source', response.id);
                    }
                    window.livewire.emit('promptpayCreateCharge');
                });
            });
        </script>
    @endif

    @if ($chargeId)
        {{--Show modal for Promptpay QR code--}}
        <div class="modal fade show" id="qrpromptpay" style="display:block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('messages.promptpay') }}</h5>
                        <button class="btn-close" id="close" wire:click="promptpayRefresh" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container mt-3 d-flex justify-content-center">
                            <div class="card border border-primary">
                                <div class="card-body border border-primary border-top-0 border-bottom-0">
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ $imageQR }}" alt="PromtPay QR Code" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <span><a href="{{ $imageQR }}" >Download QR Code <br>(ดาวน์โหลด  คิวอาร์โค๊ด)</a></span>
                                </div>
                                <div class="card-footer bg-white border border-primary text-primary mt-2">* สแกน QR Code เพื่อชำระพร้อมเพย์</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" id="backdrop" style="display: block;"></div>           
        <script>
            var close = document.querySelector("#close");

            var intervalId = setInterval(function() {
                window.livewire.emit('checkPaymentStatus');
            }, 5000);

            close.addEventListener("click", (event) => {
                event.preventDefault();
                clearInterval(intervalId);
            });

            window.livewire.on('paymentStatusUpdated', (newPaymentStatus) => {
                if (newPaymentStatus === '{{ SUCCESSFUL }}') {
                    clearInterval(intervalId); //break interval and emit event
                    window.livewire.emit('paidByPromptpay');
                }
            });
        </script>
    @endif

    <!-- Loading state-->
    @if ($paymentStatus == SUCCESSFUL)
    @include('loading.loading')
    @endif
</div>