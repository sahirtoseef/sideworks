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
        <div class="row">
          <div class="col-12">            
            <form class="registration_form postform">
              <h4 class="section-title text-white">Register</h4>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="fname" placeholder="First Name" name="fname" required />
              </div>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="lname" placeholder="Last Name" name="lname" required />
              </div>
              
              <div class="form-group">
                <input type="email" class="form-control rounded-pill" id="email" placeholder="Email" name="email" required />
              </div>
              
              <div class="form-group row">
                <div class="col">
                <input type="text" class="form-control rounded-pill" id="mobile1" placeholder="Mobile Number" name="mobile1" maxlength="3" required />
                </div>
                <div class="col">
                <input type="text" class="form-control rounded-pill" id="mobile2" placeholder="Mobile Number" name="mobile2" maxlength="3" required />
                </div>
                <div class="col-sm-5">
                <input type="text" class="form-control rounded-pill" id="mobile3" placeholder="Mobile Number" name="mobile3" maxlength="4" required />
                </div>
              </div>

              <div class="form-group">
                <input type="password" class="form-control rounded-pill" id="pwd" placeholder="Password" name="pwd" required />
              </div>
              <div class="form-group">
                <input type="password" class="form-control rounded-pill" id="cpwd" placeholder="Confim Password" name="cpwd" required />
              </div>
              <div class="form-group">
                <div class="response"></div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::register" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">Create Account</button></p>
                  <a href="<?= base_url('login'); ?>" class="btn-inline">Login</a> | <a href="<?= base_url('check'); ?>" class="btn-inline">Resend Verification Code</a> | <a href="<?= base_url('forgotten'); ?>">Forgot Password?</a>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>
<script>
    var password = document.getElementById("pwd"), confirm_password = document.getElementById("cpwd");

      function validatePassword(){
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords and confirm password must be same.");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>
<?php $this->load->view('public/layouts/footer'); ?>