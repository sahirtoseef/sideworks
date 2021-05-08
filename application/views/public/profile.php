<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/layouts/header');
$this->data['scriptInject'] = "<script>
$('.token').bind('change',function(e){
  alert($(this).val());
});
</script>";
?>
<div class="main-page bgimage reg_bgimg">
    <div class="container-lg">
        <div class="row">
          <div class="col-12">
            <div class="page-title text-center">
              <h3 class="text-white text-uppercase"><a href="<?= base_url(); ?>" class="text-white">Well pass</a></h3>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">            
            <form class="registration_form postform" response='{"CHANGES_SAVED":"Changes Saved! <a href=\"<?= base_url(); ?>\" class=\"badge badge-success\">Go to home page</a>"}'>
              <h4 class="section-title text-white">Profile</h4>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="fname" placeholder="First Name" name="fname" required value="<?= $user->info->fname; ?>" />
              </div>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="lname" placeholder="Last Name" name="lname" required value="<?= $user->info->lname; ?>" />
              </div>
              
              <div class="form-group">
                <input type="email" class="form-control rounded-pill" id="email" placeholder="Email" name="email" required value="<?= $user->info->uemail; ?>" />
              </div>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="mobile" placeholder="Mobile Number" name="mobile" required value="<?= $user->info->umobile; ?>" />
              </div>
              <div class="form-group">
                <textarea class="form-control rounded-pill" id="meta" placeholder="Address" name="meta[addr]" required ><?= $user->get_meta('addr'); ?></textarea>
              </div>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="city" placeholder="Name of the city" name="meta[city]" required value="<?= $user->get_meta('city'); ?>" />
              </div>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="state" placeholder="Name of the state" name="meta[state]" required value="<?= $user->get_meta('state'); ?>" />
              </div>
              <div class="form-group">
                <input type="text" class="form-control rounded-pill" id="zip" placeholder="Zip Code" name="meta[zip]" required value="<?= $user->get_meta('zip'); ?>" />
              </div>
              <div class="form-group">
                <div class="response"></div>
                <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                <input type="hidden" id="action" name="action" value="user::saveprofile" />
              </div>
              <div class="row">
                <div class="col-12 text-center mt-3 mb-5">
                  <p><button type="submit" class="btn btn-primary gradient-button rounded-pill">SAVE PROFILE</button></p>
                </div>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>

<?php $this->load->view('public/layouts/footer', $this->data); ?>