$(document).ready(function () {
    $("#open-Subscription-modal").click();
    // Create a Stripe client.

    var stripe = Stripe('pk_test_51GwELIK5SYYCpcUiiPhr8v64xQYQedQUAwIyPfXRu7o8k2tvlbfu0VgSX7Jbqr8HWDc0kbjuYJbStGZ7S7IGMPIo00l8IAlqAN');

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.on('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    /*start process*/
    $(document).ready(function () {
        $('#main_body').hide();
        $(document).on('click', '#submit_payment', function (e) {
            swal("You will be charged $10/month on recurring basis!")
                    .then((value) => {
                        if (value == true) {
                            stripe.createToken(card).then(function (result) {
                                if (result.error) {
                                    // Inform the user if there was an error.
                                    var errorElement = document.getElementById('card-errors');
                                    errorElement.textContent = result.error.message;
                                } else {
                                    // Send the token to your server.
                                    var value = result.token;
                                    if (result.token != '') {
                                        $('#stripetokenvalue').val(value.id)
                                        $('#stripetokenform').submit();
                                        //~ $.ajax({
                                        //~ method: "POST",
                                        //~ url: $('#action').val(),
                                        //~ data: $('#stripetokenform').serialize()
                                        //~ })
                                        //~ .success(function( msg ) {
                                        //~ alert( "Data Saved: " + msg );
                                        //~ } );
                                    } else {
                                        alert('Error in submitting the form please try again later');
                                        return false;
                                    }
                                }
                            });
                        }
                    });
        });
    });
});