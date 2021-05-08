<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$emplist = $user->getEmployerList(1);
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
            <form class="registration_form postform">
              <h4 class="section-title text-white">Verify your email address</h4>
              
              <div class="form-group">
                <input list="employers" type="text" class="form-control rounded-pill" id="email" placeholder="Email Address" name="email" value="<?= $user->isLogin() ? $user->info->uemail : ''; ?>" />
              </div>
              
              <div class="form-group">               
                  <div class="response">
                       <?= isset($_GET['fail']) && $_GET['fail'] == 'INVALID' ? '<div class="alert alert-danger text-center">Verification link was expired or invalid. Retry with another link.</div>' : ''; ?>
                  </div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::sendvermail" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">SEND VERIFICATION MAIL</button></p>                 
                </div>
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <a href="<?= base_url('login'); ?>" class="btn-inline">Login</a> | <a href="<?= base_url('register'); ?>" class="btn-inline">Register</a> | <a href="<?= base_url('check'); ?>" class="btn-inline">Resend Verification Code</a> | <a href="<?= base_url('forgotten'); ?>" class="btn-inline">Forgot Password?</a>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer'); ?>