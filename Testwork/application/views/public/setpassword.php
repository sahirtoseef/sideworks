<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/layouts/header');
?>
<div class="main-page bgimage reg_bgimg">
    <div class="container-lg vh-100">
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
              <h4 class="section-title text-white">Set Password</h4>
              
              <div class="form-group">
                <input type="password" class="form-control rounded-pill" id="pwd" placeholder="Password" name="pwd" required />
              </div>
              <div class="form-group">
                <input type="password" class="form-control rounded-pill" id="cpwd" placeholder="Confim Password" name="cpwd" required />
              </div>
              <div class="form-group">
                <div class="response"></div>
                <input type="hidden" id="utoken" name="utoken" value="<?= encrypt($udata->ukey, 'X'); ?>" />
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::setpassword" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">Set Password</button></p>
                  
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