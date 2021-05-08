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
            <?php /*<form class="registration_form postform" redirect="<?= base_url(); ?>">
              <h4 class="section-title text-white text-shadow">Are you employer?</h4>
              
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="empname" placeholder="Give Employer Name" name="emp">
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="storeid" placeholder="Store ID" name="storeid">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control rounded-pill" id="phone" placeholder="Phone" name="phone">
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
                  <h5 class="text-white text-center">OR</h5>
                </div>
              </div>
            </form> */
            ?>
            <form class="registration_form postform" redirect="<?= base_url(); ?>">

              <div class="row">
                   <div style=" text-align:center; height: 80px; " class="col-md-12 form-group">
                   <br>
                    <p style="font-size:27px; color: black; font-family: Arial, Helvetica, sans-serif;" >Please type the Name, City, State or Store ID</p>
            
                    <!--<input type="text" name="empname" class="form-control" placeholder="Name of your employer">-->
                  <!--</div>
                  <div class="col-md-5 form-group">-->

                    <!--<input type="text" name="city_or_state" class="form-control" placeholder="Select city or state">
                  </div>
                  <div class="col-md-auto form-group">
                    <button type="button" class="form-control btn btn-primary gradient-button rounded-pill search-btn"><span class="fa fa-search"></span></button>-->
                  </div>
              </div>
           
              <?php 
                  if (!$this->user->isAdmin()):
              ?>
              <!--<h4 class="section-title text-white text-shadow">Are you employee?</h4>-->
              <?php endif; ?>
              <div class="form-group">
              
                <input list="employers" type="text" class="form-control rounded-pill" id="emp" autocomplete="off" placeholder="Type name of employer and select" name="emp" value="<?= ($user->info) ? $user->info->employer : ''; ?>" />
              </div>
              <datalist id="employers" style="display:none;">
              	<?php foreach($emplist as $emp){
              		echo "<option value='".$emp->empname."'>ID ".$emp->storeid .' - '.$emp->addr . ' -'. $emp->ecity.' ,'. $emp->estate ."</option>";
              	}?>
              </datalist>
              <div class="form-group">               
                  <div class="response">
                       
                  </div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::employer::save" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">Employer</button></p>
                 
                </div>
              </div>
            </form>
            
          </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer'); ?>