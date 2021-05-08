<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/layouts/header', $this->data);
$this->data['scriptInject'] = "";
$points = (int)$result->Result;
$created = strtotime($result->created);
//var_dump($result);
if($points >= 40 && $points < 50){
  $status = 'warning';
}elseif($points >= 50){
  $status = 'danger';
}else{
  $status = 'success';
}
?>
<div class="main-page bgimage circle_bgimg">
    <div class="container-xl">
        <div class="row">
          <div class="col-12">
            <div class="featured_box  failed_box">
              <div class="section-title <?= $status == 'success' ? 'bg-gradient-danger' : 'bg-gradient-danger'; ?>">
                <div class="dropdown  dropright">
                      <button class="btn-inline dropdown-toggle invisible" type="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></button>
                      <div id="dropdownMenu" class="dropdown-menu text-center pt-2 pb-2" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-close text-danger" title="Close Menu" onclick="$('#dropdownMenu').removeClass('show');"><i class="fa fa-window-close"></i></a>
                        <a class="dropdown-item" href="<?= base_url(); ?>"><?= $user->info->employer; ?></a>
                        <a class="dropdown-item" href="#"><?= $user->info->fname; ?> <?= $user->info->lname; ?><br /><strong>(<?= userRole($user->info->urole); ?>)</strong></a>
                        <a class="dropdown-item" href="<?= base_url('profile'); ?>">Profile</a>
                        <?php if($user->isAdmin()){ ?>
                          <a class="dropdown-item" href="<?= base_url('admin'); ?>">Dashboard</a>
                        <?php } ?>
                        <?php if($status == 'danger'){ ?>
                          <a class="dropdown-item bg-danger text-white text-center mb-2 mt-2 font-weight-bold" href="#">Not Cleared</a>
                        <?php } ?>
                        <?php if($status == 'success'){ ?>
                          <a class="dropdown-item bg-success text-white text-center mb-2  mt-2 font-weight-bold" href="#">Cleared</a>
                        <?php } ?>
                        <?php if($status == 'warning'){ ?>
                          <a class="dropdown-item bg-warning text-white text-center mb-2 mt-2 font-weight-bold" href="#">May be At risk</a>
                        <?php } ?>
                        <?php if(!$user->isAdmin()){ ?>
                        <a class="dropdown-item" href="<?= base_url('test'); ?>">Retake Test</a> 
                        <?php }?>
                        <a class="dropdown-item" href="<?= base_url('signout'); ?>"><strong>Logout</strong></a>
                      </div>
                    </div>
                <div class="row m-0">
                  <div class="col-2 p-0"><a class="btn btn-default" href="<?= base_url(); ?>">
                  <i class="fa fa-home"></i>
                  </a>
                  </div>
                  <div class="col-8 p-0 text-center"><h4 class=" p-3 m-0 text-white position-relative text-center"><?= $user->info->employer; ?>
                  
                  </h4></div>
                  <div class="col-2 p-0 text-right">
                    <button class="btn btn-default" id="dropdownMenuButton" type="button" onclick="$('#dropdownMenu').toggleClass('show');"><i class="fa fa-bars"></i></button>
                     
                  </div>
                </div>
              </div>
               
             
              <div class="featured_content text-center">
                
                <?php if($status == 'danger'){ ?>
                <h4 class="section-title text-danger pt-5 pb-3"><div class="display-4"><i class="fa fa-exclamation-triangle"></i></div>Not Cleared</h4>
                <p class="mb-3 pt-3">You are not cleared to report to work.  Please contact your healthcare provider and schedule a COVID-19 test.</p>
               
                <?php } ?>
                <?php if($status == 'warning'){ ?>
                <h4 class="section-title text-warning pt-5 pb-3"><div class="display-4"><i class="fa fa-exclamation-triangle"></i></div> May be at Risk</h4>
                <p class="mb-3 pt-3">You are not cleared to report to work.  Please contact your healthcare provider and schedule a COVID-19 test.</p>
                
                <?php } ?>
                <?php if($status == 'success'){ ?>
                <h4 class="section-title text-success pt-5 pb-3"><div class="display-4"><i class="fa fa-check-circle"></i></div> Cleared</h4>
                <p class="mb-3 pt-3">You are cleared for work and may start your shift.</p>
                
                <?php } ?>
                 <div class="time_box">
                  <h2 class="text-uppercase text-grey"><?= date('D',$created); ?></h2>
                  <p class="date_time"><?= date('m/d/Y H:i:s',$created); ?></p>
                  <h3 class="text-uppercase text-dark font-weight-bolder"><?= $user->info->fname; ?> <?= $user->info->lname; ?></h3>
                  
                <?php if(!$user->isAdmin()){ ?>
                    <div class="pt-5"><a href="<?= base_url('test'); ?>" class="btn btn-primary text-white gradient-button rounded-pill">Retake Test</a></div>
                <?php }?>
                       
                      
                </div>
                <div class="featured_bottom">
                  <p class="mb-0">A copy of your attachments has been sent to your email <?= $user->info->uemail;?> and your employer <?php echo !empty($this->user->getEmployer()) ? $this->user->getEmployer()->email : "-"; ?>.</p>
                  <br>
                  <p class="mb-0">If you need assistance, please call your healthcare provider.</p>
                  <p class="mb-0">Have a great day!</p>
                  <br>
                  <p class="mb-0">--------------------------------------------------------</p>
                  <p class="mb-0"><b>WellPassToday.com</b></p>
                  <p class="mb-0"><b>WellPassPro.com</b></p>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer', $this->data); ?>