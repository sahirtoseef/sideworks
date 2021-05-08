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
              <h4 class="section-title text-white text-shadow">Employer Registration</h4>
              
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="empname" placeholder="Employer Name" name="emp">
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="storeid" placeholder="Store ID" name="storeid">
                  </div>
                </div>
                <div class="col-md-12">
                <div class="form-group row">
                <div class="col">
                <input type="text" class="form-control rounded-pill" id="phone1" placeholder="Phone" name="phone1" maxlength="3" required />
                </div>
                <div class="col">
                <input type="text" class="form-control rounded-pill" id="phone2" placeholder="Phone" name="phone2" maxlength="3" required />
                </div>
                <div class="col-sm-5">
                <input type="text" class="form-control rounded-pill" id="phone3" placeholder="Phone" name="phone3" maxlength="4" required />
                </div>
              </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="addr" placeholder="Address" name="addr">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="state" placeholder="State" name="state">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="city" placeholder="City" name="city">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="zip" placeholder="Zip" name="zip">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <input type="email" class="form-control rounded-pill" id="email" placeholder="Email" name="email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="password" class="form-control rounded-pill" id="password" placeholder="Password" name="password">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="password" class="form-control rounded-pill" id="confirm_password" placeholder="Password Confirmation" name="confirm_password">
                  </div>
                </div>
                
              </div>
              <div class="form-group">               
                  <div class="response">
                    <?php if(isset($_GET['check']) && $_GET['check']=='verified'){ ?> 
                                <div class="alert alert-primary" role="alert">
                                    <?php echo 'Your account is verified. if you are employer then give name of company / retail!'; ?>
                                </div>
                    <?php } ?>
                  </div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::employer::create" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">Save as Employer</button></p>
                </div>
              </div>
            </form>            
          </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer'); ?>