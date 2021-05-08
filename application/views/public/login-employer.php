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
            <form class="registration_form postform" redirect="<?= base_url(); ?>">
              <h4 class="section-title text-white">Employer Sign In</h4>
              
              <div class="form-group">
                <input type="email" class="form-control rounded-pill" id="user" placeholder="Email" name="user">
              </div>
              <div class="form-group">
                <input type="password" class="form-control rounded-pill" id="pwd" placeholder="Password" name="pwd">
              </div>
              <div class="form-group">               
                  <div class="response">
                    <?php if(isset($_GET['check']) && $_GET['check']=='verified'){ ?> 
                                <div class="alert alert-primary" role="alert">
                                    <?php echo 'Your account is verified. you can login now!'; ?>
                                </div>
                    <?php } ?>
                  </div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::login" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">Login</button></p>
                  <a href="<?= base_url('register'); ?>" class="btn-inline">Register</a> | <a href="<?= base_url('check'); ?>" class="btn-inline">Resend Verification Code</a> | <a href="<?= base_url('forgotten'); ?>" class="btn-inline">Forgot Password?</a>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer'); ?>