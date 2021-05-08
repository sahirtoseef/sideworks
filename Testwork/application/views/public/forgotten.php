<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/layouts/header');
?>
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
            <form class="registration_form postform" method="POST">
              <h4 class="section-title text-white">Reset Password</h4>
              
              <div class="form-group">
                <input type="email" class="form-control rounded-pill" id="email" placeholder="Email" name="email" required />
              </div>
              
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <div class="response"></div>
                  <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                  <input type="hidden" id="action" name="action" value="user::sendreset" />
                  
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">Send Reset Link</button></p>
                  <a href="<?= base_url('register'); ?>" class="btn-inline">Register</a> | <a href="<?= base_url('login'); ?>" class="btn-inline">Login</a>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer'); ?>