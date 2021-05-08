<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->data['title'] = "Blling";

$emplist = $user->getEmployerList();
//~ print_r($emplist);die;
  $emps = '<div class="form-group"><input list="employers" type="text" class="form-control rounded-pill" id="empname" placeholder="Type name of employer and select" name="empname" value="" required /></div>';
if($user->isSuperAdmin()){           
      $empsdata = '<datalist id="employers" style="display:none;">';
    	foreach($emplist as $emp){
    		$empsdata .= "<option value='".$emp->empname."'>".$emp->storeid .' - '.$emp->estate."</option>";
    	}
      $empsdata .= '</datalist>';
}else{
  $empsdata = '<datalist id="employers" style="display:none;">';
  $empsdata .= "<option value='".$user->info->employer."'></option>";
   $empsdata .= '</datalist>'; 
}

$response_id = '';


$response = $this->data;

$billing_status = isset($response['user']->info->subscription_status) ? $response['user']->info->subscription_status : '';

if($billing_status != '') {
	header("Location: /admin/"); 
	exit;
}

$this->load->view('Admin/layouts/header', $this->data);
	
?>
<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>


<style>
    .navbar-nav{
        display:none;
    }
	.card {
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 1px 1px transparent;
            flex: 1;
            margin: 8px;
            padding: 30px;
            position: relative;
            text-align: center;
            transition: all 0.5s ease-in-out;
        }

        .card.popular {
            margin-top: -10px;
            margin-bottom: -10px;
        }

        .card.popular .card-title h3 {
            color: #3498db;
            font-size: 22px;
        }

        .card.popular .card-price {
            margin: 50px;
        }

        .card.popular .card-price h1 {
            color: #3498db;
            font-size: 60px;
        }
          .card.popular .card-action button {
            background-color: #3498db;
            border-radius: 80px;
            color: #fff;
            font-size: 17px;
            margin-top: -15px;
            padding: 15px;
            height: 80px;
        }

        .card.popular .card-action button:hover {
            background-color: #2386c8;
            font-size: 23px;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .card-ribbon {
            position: absolute;
            overflow: hidden;
            top: -10px;
            left: -10px;
            width: 114px;
            height: 112px;
        }

        .card-ribbon span {
            position: absolute;
            display: block;
            width: 160px;
            padding: 10px 0;
            background-color: #3498db;
            box-shadow: 0 5px 5px rgba(0, 0, 0, 0.2);
            color: #fff;
            font-size: 13px;
            text-transform: uppercase;
            text-align: center;
            left: -35px;
            top: 25px;
            transform: rotate(-45deg);
        }
		
		  .card-ribbon::before,
        .card-ribbon::after {
            position: absolute;
            z-index: -1;
            content: '';
            display: block;
            border: 5px solid #2980b9;
            border-top-color: transparent;
            border-left-color: transparent;
        }

        .card-ribbon::before {
            top: 0;
            right: 0;
        }

        .card-ribbon::after {
            bottom: 0;
            left: 0;
        }

        .card-title h3 {
            color: rgba(0, 0, 0, 0.3);
            font-size: 15px;
            text-transform: uppercase;
        }

        .card-title h4 {
            color: rgba(0, 0, 0, 0.6);
        }

        .card-price {
            margin: 60px 0;
        }

        .card-price h1 {
            font-size: 46px;
        }

        .card-price h1 sup {
            font-size: 15px;
            display: inline-block;
            margin-left: -20px;
            width: 10px;
        }

        .card-price h1 small {
            color: rgba(0, 0, 0, 0.3);
            display: block;
            font-size: 11px;
            text-transform: uppercase;
        }

        .card-description ul {
            display: block;
            list-style: none;
            margin: 60px 0;
            padding: 0;
        }

        .card-description li {
            color: rgba(0, 0, 0, 0.6);
            font-size: 15px;
            margin: 0 0 15px;
        }

        .card-description li::before {
            font-family: FontAwesome;
            content: "\f00c";
            padding: 0 5px 0 0;
            color: rgba(0, 0, 0, 0.15);
        }

        .card-action button {
            background: transparent;
            border: 2px solid #3498db;
            border-radius: 30px;
            color: #3498db;
            cursor: pointer;
            display: block;
            font-size: 15px;
            font-weight: bold;
            padding: 20px;
            width: 100%;
            height: 60px;
            text-transform: uppercase;
            transition: all 0.3s ease-in-out;
        }

        .card-action button:hover {
            background-color: #3498db;
            box-shadow: 0 2px 4px #196090;
            color: #fff;
            font-size: 17px;
        }
        input,
.StripeElement {
  height: 40px;
  padding: 10px 12px;

  color: #32325d;
  background-color: white;
  border: 1px solid transparent;
  border-radius: 4px;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

input:focus,
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
    
 <div class="main-page bgimage reg_bgimg">
    <div class="container-lg vh-100">
        <div class="wrapper-card">
        <div class="card popular">
            <div class="card-ribbon" id="billing_page">
                <span>Best Price</span>
            </div>
            <div class="card-title">
                <h4>We can help you keep employees safe and employers informed of health risks</h4>
                <h4> in their facility.</h4>
            </div>
            <div class="card-price">
                <h1>
                <sup>$</sup>
                10.00/month
                <small>*Recurring monthly</small>
            </h1>
            </div>
            <div class="card-body">
                <h2>Per location - unlimited employees</h2>
                <h5>Start with a 7 Days Free Trial.</h5>
            </div>
            <div class="card-description">
                <ul>
                    <li>Get constant information about your employers health.</li>
                    <li>Have data at your fingertips.</li>
                    <li>Cost effective.</li>
                    <li>Help you provide peace of mind to your customers.</li>
                    <li>Cancel Anytime.</li>
                </ul>
            </div>
            <div class="card-action">
                <button type="button" id="getsubcription" data-toggle="modal" data-target="#exampleModal">Get Pro</button>
            </div>
        </div>
    </div>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Enter Your card Details</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			  <form id="stripetokenform" class="registration_form postform">
			  <div class="col-md-12">
					<label for="card-element">Credit or debit card</label>
					<div id="card-element" class="form-control rounded-pill"></div>
					<!-- Used to display form errors. -->
					<div id="card-errors" role="alert"></div>
				  </div>
				  <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" /> 
				<input type="hidden" name="stripe_token" id="stripetokenvalue">
				<input type="hidden" id="action" name="action" value="user::employer::update" />
			  <!-- Used to display form errors. -->
			  <div id="error-message" role="alert"></div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="submit_payment">Pay</button>
			</form>
		  </div>
		</div>
	  </div>
	</div>
    <script>
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
		card.on('change', function(event) {
		  var displayError = document.getElementById('card-errors');
		  if (event.error) {
			displayError.textContent = event.error.message;
		  } else {
			displayError.textContent = '';
		  }
		});

		/*start process*/
		$(document).ready(function() {
			$('#main_body').hide();
			$(document).on('click', '#submit_payment', function(e){
				swal("You will be charged $10/month on recurring basis!")
				.then((value) => {
					if(value == true) {
						stripe.createToken(card).then(function(result) {
							if (result.error) {
							  // Inform the user if there was an error.
							  var errorElement = document.getElementById('card-errors');
							  errorElement.textContent = result.error.message;
							} else {
							  // Send the token to your server.
							 var value = result.token;
							 if(result.token != '') {
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
	</script>	
    </div>
</div> 
<?php $this->load->view('Admin/layouts/footer', $this->data); ?>
