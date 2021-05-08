<?php $this->load->view('Admin/layouts/header'); ?>
<script
    src="https://code.jquery.com/jquery-3.5.1.js"
    integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
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
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Content Row -->
<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Submissions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($submission); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Submissions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($pending); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($getStripeEmployerDetails) { ?>
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Billing</h1>
    </div>
    <!-- Content Row -->
    <div class="col-xl-4 col-md-8 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Payment Activity</div>
                        <div class="h5 mt-20 font-weight-light text-green-800" style="margin-bottom: 30px;margin-top: 10px;"><?php echo $getStripeEmployerDetails->empname . " # " . $getStripeEmployerDetails->storeid; ?></div>
                        <!--<a href="/admin/buy_subscription" class="btn btn-sm btn-success text-white">Make a payments</a>-->
                    </div>
                    <div class="col-auto">
                        <a href="/admin/billing" class="h5 mb-0 font-weight-bold">Transaction Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
</div>
<!-- Content Row -->

<div class="row">
    <div class="col-md-12">
        <?php
        foreach ($notifications as $not) {
            $modifier = $user->getInfo($not->byuid)
            ?>
            <div class="card mb-4 py-3 border-left-<?= errtype($not->ntype); ?>">
                <div class="card-body">
                    <div class="row">

                        <div class="display-4 col-md-1 text-center text-<?= errtype($not->ntype); ?>">
                            <?= icon(errtype($not->ntype)); ?>
                        </div>
                        <div class="col-md-11">
                            <h4 class="text-<?= errtype($not->ntype); ?>"><?= $not->nlabel; ?> <small class="h6 text-muted text-bold">@<?= date('m-d-Y H:i:s', strtotime($not->created)); ?></small></h4>

                            <div><?= $not->ntext; ?> 
                                <span class="text-<?= errtype($not->ntype); ?>">Changes made by <?php
                                    if (isset($not->uemail) && isset($modifier->uemail) && $not->uemail == $modifier->uemail) {
                                        echo "Self";
                                    } elseif (is_null($modifier)) {
                                        echo "[USER_DELETED_OR_NOT_AVAILABLE]";
                                    } else {
                                        echo $modifier->fname . " " . $modifier->lname . " [" . $modifier->uemail . "]";
                                    }
                                    ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<?php if ($subscriptionPopup) { ?>
    <button type="button" class="btn btn-primary" id="open-Subscription-modal" data-toggle="modal" data-target="#exampleModalCenter" style="display:none;"></button>
    <div id="exampleModalCenter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Subscription</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card popular">
                        <div class="card-ribbon" id="billing_page">
                            <span>Best Price</span>
                        </div>
                        <div class="card-title">
                            <h4>We can help you keep employees safe and employers</h4>
                            <h4> informed of health risks in their facility.</h4>
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
                            <button type="button" id="getsubcription" class="close" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#exampleModal">Get Pro</button>
                        </div>
                    </div>
                </div>
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
<?php } ?>

<!-- Content Row -->

<?php $this->load->view('Admin/layouts/footer'); ?>