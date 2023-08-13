<div>
    @if (empty($charge)) 
        <form id="checkoutPromptpayForm" wire:submit.prevent="promptpay">
            <input type="hidden" name="omiseSource" wire:model.defer="source">
            <button type="submit" wire:loading.attr="disabled" class="form-control bg_own_color" wire:loading.attr="disabled" id="checkoutPromptpayButton">Pay with Promptpay</button>
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
                    window.livewire.emit('promptpay');
                });
            });
        </script>
    @endif

    @if (!empty($charge))
        {{--Show modal boostrap to quick view detail order--}}
        <div class="modal fade show" id="qrpromptpay" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('messages.promptpay') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container mt-3 d-flex justify-content-center">
                            <div class="card border border-primary">
                                <div class="card-body border border-primary border-top-0 border-bottom-0">
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ $charge['scannable_code']['image']['download_uri']; }}" alt="PromtPay QR Code" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <span><a href="{{ $charge['scannable_code']['image']['download_uri']; }}" >Download QR Code <br>(ดาวน์โหลด  คิวอาร์โค๊ด)</a></span>
                                </div>
                                <div class="card-footer bg-white border border-primary text-primary mt-2">* สแกน QR Code เพื่อชำระพร้อมเพย์</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Let's also add the backdrop / overlay here -->
        <script type="module">
            $(document).ready(function() {
                var qrpromptpay = new bootstrap.Modal(document.getElementById('qrpromptpay'));
                qrpromptpay.show();
                $('#qrpromptpay').on('hidden.bs.modal', function() {
                    window.livewire.emit('refresh');
                });
            });
        </script>
    @endif
</div>