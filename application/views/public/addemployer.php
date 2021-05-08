<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$emplist = $user->getEmployerList(1);
$this->load->view('public/layouts/header');
?>
<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
<style>
	.StripeElement {
	  box-sizing: border-box;

	  height: 40px;

	  padding: 10px 12px;

	  border: 1px solid transparent;
	  border-radius: 4px;
	  background-color: white;

	  box-shadow: 0 1px 3px 0 #e6ebf1;
	  -webkit-transition: box-shadow 150ms ease;
	  transition: box-shadow 150ms ease;
	}

	.StripeElement--focus {
	  box-shadow: 0 1px 3px 0 #cfd7df;
	}

	.StripeElement--invalid {
	  border-color: #fa755a;
	}

	.StripeElement--webkit-autofill {
	  background-color: #fefde5 !important;
	}
</style>
<!--<script src="https://js.stripe.com/v3/"></script>-->         
<div class="main-page bgimage reg_bgimg">
    <div class="container-lg vh-100">
        <?php $this->load->view('public/common/menu.php') ?>
        <div class="row">
          <div class="col-12">
            <div class="page-title text-center">
              <h3 class="text-white text-uppercase">Well pass</h3>
            </div>
          </div>
        </div>
        <div class="row vh-70">
          <div class="col-12 align-self-center">            
            <form class="registration_form postform" id="paymentform">
              <h4 class="section-title text-white text-shadow">Employer Registration</h4>
              
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="empname" placeholder="Employer Name" name="emp">
              </div>
              <div class="row">
<!--                <div class="col-md-12">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="storeid" placeholder="Store ID" name="storeid">
                  </div>
                </div>-->
                <div class="col-md-12">
                <div class="form-group row">
                <div class="col">
                <input type="text" class="form-control rounded-pill" id="phone1" placeholder="Phone" name="phone1" maxlength="3" required />
                </div>
                <div class="col">
                <input type="text" class="form-control rounded-pill" id="phone2" placeholder="Phone" name="phone2" maxlength="3" required />
                </div>
                <div class="col-sm-5">
                <input type="text" class="form-control rounded-pill" id="phone3" placeholder="Phone" name="phone3" maxlength="4" required />
                </div>
              </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="addr" placeholder="Address" name="addr">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="state" placeholder="State" name="state">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="city" placeholder="City" name="city">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="zip" placeholder="Zip" name="zip">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <input type="email" class="form-control rounded-pill" id="email" placeholder="Email" name="email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="password" class="form-control rounded-pill" id="password" placeholder="Password" name="password">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="password" class="form-control rounded-pill" id="confirm_password" placeholder="Password Confirmation" name="confirm_password">
                  </div>
                </div>
<!--				  <div class="col-md-12">
					<label for="card-element">Credit or debit card</label>
					<div id="card-element" class="form-control rounded-pill"></div>
					 Used to display form errors. 
					<div id="card-errors" role="alert"></div>
				  </div>-->
                
              </div>
              <div class="form-group">               
                  <div class="response">
                    <?php if(isset($_GET['check']) && $_GET['check']=='verified'){ ?> 
                                <div class="alert alert-primary" role="alert">
                                    <?php echo 'Your account is verified. if you are employer then give name of company / retail!'; ?>
                                </div>
                    <?php } ?>
                  </div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::employer::create" />
                 <input type="hidden" id="stripetokenvalue" name="stripetoken" />
              </div>
				
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" id="payment-form-btn" class="btn btn-primary gradient-button rounded-pill">Save as Employer</button></p>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>
<script>
//	// Create a Stripe client.
//	var stripe = Stripe('pk_test_51GwELIK5SYYCpcUiiPhr8v64xQYQedQUAwIyPfXRu7o8k2tvlbfu0VgSX7Jbqr8HWDc0kbjuYJbStGZ7S7IGMPIo00l8IAlqAN');
//
//	// Create an instance of Elements.
//	var elements = stripe.elements();
//
//	// Custom styling can be passed to options when creating an Element.
//	// (Note that this demo uses a wider set of styles than the guide below.)
//	var style = {
//	  base: {
//		color: '#32325d',
//		fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
//		fontSmoothing: 'antialiased',
//		fontSize: '16px',
//		'::placeholder': {
//		  color: '#aab7c4'
//		}
//	  },
//	  invalid: {
//		color: '#fa755a',
//		iconColor: '#fa755a'
//	  }
//	};
//
//	// Create an instance of the card Element.
//	var card = elements.create('card', {style: style});
//
//	// Add an instance of the card Element into the `card-element` <div>.
//	card.mount('#card-element');
//
//	// Handle real-time validation errors from the card Element.
//	card.on('change', function(event) {
//	  var displayError = document.getElementById('card-errors');
//	  if (event.error) {
//		displayError.textContent = event.error.message;
//	  } else {
//		displayError.textContent = '';
//	  }
//	});
	
	/*payment form button*/
//	$(document).ready(function() {
//		$(document).on('click', '#payment-form-btn', function(e) {
//                    alert();
//			e.preventDefault();
//			var token_value = $( "input[type=hidden][name=stripeToken]" ).val();	
//			console.log('I got token', token_value);
//			if(token_value == '' || token_value == undefined || token_value == 'undefined') {
//			  stripe.createToken(card).then(function(result) {
//				if (result.error) {
//				  // Inform the user if there was an error.
//				  var errorElement = document.getElementById('card-errors');
//				  errorElement.textContent = result.error.message;
//				} else {
//				  // Send the token to your server.
//				  var value = result.token;
//				  if(result.token != '') {
//					  $('#stripetokenvalue').val(value.id)
//					  $('#paymentform').submit(); 
//				  } else {
//					  alert('Error in submitting the form please try again later');
//					  return false;
//				  }
//				}
//			  });
//			} else {
//			 $('#paymentform').submit(); 
//			}
//		});
//	});

</script>
<?php $this->load->view('public/layouts/footer'); ?>
