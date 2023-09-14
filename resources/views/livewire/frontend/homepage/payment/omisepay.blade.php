<div>
    <form id="checkoutCardForm" wire:submit.prevent="pay">
        <input type="hidden" name="omiseToken" wire:model.defer="token">
        <input type="hidden" name="omiseSource" wire:model.defer="source">
        <button type="submit" wire:loading.attr="disabled" class="form-control bg_own_color" wire:loading.class="loading" id="checkoutCardButton">
            <span wire:loading.remove>{{ trans('messages.paywithcard') }}</span>
            <span wire:loading>{{ trans('messages.loading') }}</span>
        </button>
        @error('paymentcard') <span class="text-danger error">{{ $message }}</span> @enderror
    </form>

    <script>
        OmiseCard.configure({
            publicKey: "{{ $publicKey }}",
            image: 'https://cdn.omise.co/assets/dashboard/images/omise-logo.png'
        });

        var buttonCard = document.querySelector("#checkoutCardButton");
        var formCard = document.querySelector("#checkoutCardForm");

        buttonCard.addEventListener("click", (event) => {
            event.preventDefault();
            OmiseCard.open({
                amount: {{ $amount }},
                currency: "thb",
                defaultPaymentMethod: "credit_card",
                onCreateTokenSuccess: (nonce) => {
                    if (nonce.startsWith("tokn_")) {
                        formCard.omiseToken.value = nonce;
                        //$set('token', nonce);
                        @this.set('token', nonce);
                    } else {
                        formCard.omiseSource.value = nonce;
                        //$set('source', nonce);
                        @this.set('source', nonce);
                    };
                    //button.click();
                    Livewire.emit('payByCard');
                }
            });
        });
    </script>

    <!-- Loading state-->
    @include('loading.loading')
</div>
