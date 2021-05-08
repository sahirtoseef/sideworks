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
              <h4 class="section-title text-white">Contact Us</h4>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="name" placeholder="Name" name="name" required />
              </div>
              
              <div class="form-group">
                <input type="email" class="form-control rounded-pill" id="email" placeholder="Email" name="email" required />
              </div>
              
              <div class="form-group">
                <textarea class="form-control rounded-pill" placeholder="Message" rows="3" style="resize: none;" name="message"></textarea>
              </div>

              <div class="form-group">
                <div class="response"></div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::contact" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">Send</button></p>
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