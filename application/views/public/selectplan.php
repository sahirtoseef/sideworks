<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$emplist = $user->getEmployerList(1);
$this->load->view('public/layouts/header');
?>
<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<style>
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
</style>	
    
 <div class="main-page bgimage reg_bgimg">
    <div class="container-lg vh-100">
		

        <?php $this->load->view('public/common/menu.php') ?>
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
                <button type="button" id="getsubcription">Sign Up Now!</button>
            </div>
        </div>
    </div>
    <script>
		/*start process*/
		$(document).ready(function() {
			$('#main_body').hide();
			$(document).on('click', '#getsubcription', function(e){
				swal("You will be charged $10/month on recurring basis!")
				.then((value) => {
					if(value == true) {
						location.href = "/addemployer";
					} 
				});
			});
		});
	</script>	
    </div>
</div> 
<?php $this->load->view('public/layouts/footer'); ?>
