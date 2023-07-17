<div>
    <section class="d-flex justify-content-center">
        <form wire:submit.prevent="processPayment">
            <!-- Include the Omise.js library -->
            <script src="https://cdn.omise.co/omise.js"></script>
            
            <!-- Add the hidden input field for storing the Omise.js token -->
            <input type="hidden" id="omiseToken" name="omiseToken">
            
            <button type="submit" class="btn btn-lg bg_own_color text-light" id="payButton">Pay with Alipay</button>
        </form>
        <script type="module">
            $("#payButton").click(function(){
                Omise.setPublicKey('{{ env('OMISE_PUBLIC_KEY') }}');
                Omise.createToken('alipay', function (statusCode, response) {console(response);
                    if (statusCode === 200) {
                        // Set the token value to the hidden input field
                        $('#omiseToken').val() = response.id;
                    } else {
                        alert('error');
                        // Handle token creation error
                    }
                });
            });
        </script>
    </section>
</div>