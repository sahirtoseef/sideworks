<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/layouts/header');
?>
<div class="main-page bgimage reg_bgimg">
    <div class="container-lg">
        <?php $this->load->view('public/common/menu.php') ?>
        <div class="row">
          <div class="col-12">
            <div class="page-title text-center">
              <h3 class="text-white text-uppercase">Well pass</h3>
            </div>
          </div>
        </div>
<div class="container">
    <div class="legal-page-content">
    <h1 class="page-title">Refund policy</h1>
    <h5>60 Day money back guarantee</h5>
    <p>Well Pass Today is proud to offer a full 60 day money back guarantee. We love our customers (and non-customers) and want the feeling to be mutual. If you are unhappy, for any reason, you may request a refund within 60 days of choosing a paid plan.</p>
    <div
    class="text-center">
        <img src="<?php echo base_url('assets/admin/img/money60days.svg') ?>" width="150" height="150">
		</div>
	</div>
</div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer'); ?>