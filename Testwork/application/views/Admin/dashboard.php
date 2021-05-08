<?php $this->load->view('Admin/layouts/header'); ?>
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
          </div>

          <!-- Content Row -->
          <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Submissions</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($submission); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Submissions</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($pending); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Notifications</h1>
          </div>
          <!-- Content Row -->
      
          <div class="row">
            <div class="col-md-12">
              <?php foreach($notifications as $not){ $modifier = $user->getInfo($not->byuid) ?>
              <div class="card mb-4 py-3 border-left-<?= errtype($not->ntype); ?>">
                <div class="card-body">
                  <div class="row">
                    
                    <div class="display-4 col-md-1 text-center text-<?= errtype($not->ntype); ?>">
                      <?= icon(errtype($not->ntype)); ?>
                    </div>
                      <div class="col-md-11">
                        <h4 class="text-<?= errtype($not->ntype); ?>"><?= $not->nlabel; ?> <small class="h6 text-muted text-bold">@<?= date('m-d-Y H:i:s', strtotime($not->created)); ?></small></h4>
                        
                        <div><?= $not->ntext; ?> 
                        <span class="text-<?= errtype($not->ntype); ?>">Changes made by <?php
                        if(isset($not->uemail) && isset($modifier->uemail) && $not->uemail == $modifier->uemail){
                            echo "Self";
                        }elseif(is_null($modifier)){
                            echo "[USER_DELETED_OR_NOT_AVAILABLE]";
                        }else{
                            echo $modifier->fname." ".$modifier->lname." [".$modifier->uemail."]";
                        }
                        ?></span></div>
                      </div>
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
            
          </div>

          <!-- Content Row -->
          


<?php $this->load->view('Admin/layouts/footer'); ?>