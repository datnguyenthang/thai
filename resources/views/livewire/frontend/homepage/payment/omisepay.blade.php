<div>
    <form id="checkoutForm" wire:submit.prevent="pay">
        <input type="hidden" name="omiseToken" wire:model.defer="token">
        <input type="hidden" name="omiseSource" wire:model.defer="source">
        <div class="payment_box text-dark">
            {!! trans('messages.omiseinfomation') !!}
        </div>
        <button type="submit" wire:loading.attr="disabled" class="form-control bg_own_color" wire:loading.attr="disabled" id="checkoutButton">Pay with Opn Payments</button>
    </form>
      
    <script type="text/javascript" src="https://cdn.omise.co/omise.js"></script>
    <script>
        OmiseCard.configure({
            publicKey: "{{ $publicKey }}"
        });
      
        var button = document.querySelector("#checkoutButton");
        var form = document.querySelector("#checkoutForm");
      
        button.addEventListener("click", (event) => {
            event.preventDefault();
            OmiseCard.open({
                amount: {{ $amount }},
                currency: "THB",
                defaultPaymentMethod: "credit_card",
                onCreateTokenSuccess: (nonce) => {
                    if (nonce.startsWith("tokn_")) {
                        form.omiseToken.value = nonce;
                        //$set('token', nonce);
                        @this.set('token', nonce);
                    } else {
                        form.omiseSource.value = nonce;
                        //$set('source', nonce);
                        @this.set('source', nonce);
                    };
                    //button.click();
                    Livewire.emit('pay');
                }
            });
        });
    </script>
</div>
