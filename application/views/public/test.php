<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('public/layouts/header', $this->data);
//var_dump($result);
if(!is_null($result)){
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
}else{
  $points = 0;
  $status = 'danger';
}
$this->data['scriptInject'] = "
  <script>
    hideNextButton('#QA_1');
    var submitButtonHtml = $('[data-submit-button]').prop('outerHTML');
    $('input[type=checkbox], input[type=radio], .btn-next').click(function(){
  		step1Check();
      calcpoints();
      if ($(this).val() == '12' || $(this).val() == '14') {
          hideNextButton('#QA_1');
          showSubmitButton('#QA_1');
      } else if ($(this).val() == '13') {
          hideSubmitButton('#QA_1');
          showNextButton('#QA_1');
      }
  	});
    function step1Check() {
        var firstSelection = $('#QA_1').find('input[type=radio]:checked').eq(0);
        console.log('Value selected='+$(firstSelection).val());
    }
    function hideNextButton(parent) {
        $(parent).find('.btn-next').hide();
    }
    function showNextButton(parent) {
        $(parent).find('.btn-next').show();
    }
    function showSubmitButton(container) {
        $(container).find('[data-action-bar]').append(submitButtonHtml);
    }
    function hideSubmitButton(container) {
        $(container).find('[data-action-bar]>[data-submit-button]').remove();
    }

  	function calcpoints(){
  		var total = 0.00;
  		$('.selector').find('input[type=checkbox]:checked, input[type=radio]:checked').each(function(){
  			total += parseFloat($(this).attr('points'));
  		});
  			if(total < 10){
  					$('.points span').removeAttr('class').attr('class','badge badge-success').html('<i class=\"fa fa-check-circle\"></i> You are cleared to work!');
  			}else if (total >= 10 && total < 40){
  				$('.points span').removeAttr('class').attr('class','badge badge-success').html('<i class=\"fa fa-check-circle\"></i> You are still cleared to work!');
  			}else if(total >= 40 && total < 50){
  				$('.points span').removeAttr('class').addClass('badge badge-warning').html('<i class=\"fa fa-exclamation-triangle\"></i> Your risk level is medium');
  			}else if(total >= 50){
  				$('.points span').removeAttr('class').addClass('badge badge-danger').html('<i class=\"fa fa-medkit\"></i> You are not cleared to work!');
  			}
  			
  			$('#points').val(total);
  		}
  </script>
";
?>
<div class="main-page bgimage lightcircle_bgimg">
    <div class="container-xl">
        <div class="row">
          <div class="col-12"> 
            <div class="featured_box failed_box que_box"> 
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
                        <a class="dropdown-item" href="<?= base_url('test'); ?>">Retake Test</a> 
                        <a class="dropdown-item" href="<?= base_url('signout'); ?>"><strong>Logout</strong></a>
                      </div>
                    </div>
                <div class="row m-0">
                  <div class="col-2 p-0"><a class="btn btn-default" href="<?= base_url(); ?>"><i class="fa fa-home"></i></a></div>
                  <div class="col-8 p-0 text-center"><h4 class=" p-3 m-0 text-white position-relative text-center"><?= $user->info->employer; ?>
                  
                  </h4></div>
                  <div class="col-2 p-0 text-right">
                    <button class="btn btn-default" id="dropdownMenuButton" type="button" onclick="$('#dropdownMenu').toggleClass('show');"><i class="fa fa-bars"></i></button>
                     
                  </div>
                </div>
              </div>
              
              <div class="form-builder">
                <form class="postform" METHOD="POST">
                  
                  <?php
                      echo '<input type="hidden" id="points" name="points" value="0" />
                                <input type="hidden" id="token" class="token" name="'.csrf('name').'" value="'.csrf().'" />
                                <input type="hidden" id="action" name="action" value="user::form::save" />';
                  ?>
                  <div class="form-content question tab-content">
                    <?php
                   $options = $qa['options']; 
                   $loop = 0;
                   $ques = $qa['questions'];
                    foreach($qa['questions'] as $que){
                      //var_dump($que);
                       // $queid = $que->id;
                       // $qtype = $que->Qtype; ['.$que->Qid.'] ['.$opt->QOID.'] 
                       $prev = isset($ques[($loop-1)]) ? $ques[($loop-1)] : false;
                       $next = isset($ques[($loop+1)]) ? $ques[($loop+1)] : false;
                       $active = $loop < 1 ? ' active ' : '';
                       $required = $que->isrequired == 1 ? 'required' : '';
                       echo "<div class='tab-pane selector $required ".$active."' id='QA_".$que->Qid."' aria-labelledby='QA_".$que->Qid."-tab'  role='tabpanel' ><h5>".$que->QLabel."</h5>";
                       foreach($options[$que->Qid] as $opt){
                         echo selectType($que->Qtype, 'A'.$que->Qid.'_'.$opt->QOID, 'QA['.$que->Qid.'][]', $opt->OpLabel, $opt->QOID, $opt->quepoints);
                       }
                      /*
                      
                      var_dump($que); echo "<br />";
                      if($que->Qid > $queid){
                        //$queid = $que->Qid;
                         echo "</ul>";
                      }*/
                      
                      //var_dump($prev); ?>
                      <div class="result text-center h3 p-2 points">
                        <span class="badge badge-default"></span>
                      </div>
                      <?php if(!$prev){
                        echo '<p class="text-center" data-action-bar><button type="button" id="QA_'.$next->Qid.'-tab" data-toggle="tab" href="#QA_'.$next->Qid.'" role="tab" aria-controls="QA_'.$next->Qid.'" class="btn btn-primary gradient-button rounded-pill btn-next">Next</button></p></div>';
                      }elseif(!$next){
                        echo '<div class="form-group text-center">
                                <div class="response"></div>
                              </div>';
                        echo '<input type="hidden" id="qs" name="qs" value="'.encrypt($que->QsID, 'X').'" />';
                        echo '<p class="text-center"><button data-submit-button type="submit" id="QA_'.$que->Qid.'-tab" class="btn btn-primary gradient-button rounded-pill btn-next">Submit</button></p>';
                       // echo '<p class="text-center"><a type="button" id="QA_'.$prev->Qid.'-tab" data-toggle="tab" href="#QA_'.$prev->Qid.'" role="tab" aria-controls="home" >Go back</a></p>';
                        echo '</div>';
                      }else{
                        echo '<p class="text-center" data-action-bar><button type="button" id="QA_'.$next->Qid.'-tab" data-toggle="tab" href="#QA_'.$next->Qid.'" role="tab" aria-controls="QA_'.$next->Qid.'" class="btn btn-primary gradient-button rounded-pill btn-next">Next</button></p>';
                       // echo '<p class="text-center"><a type="button" id="QA_'.$prev->Qid.'-tab" data-toggle="tab" href="#QA_'.$prev->Qid.'" role="tab" aria-controls="home" >Go back</a></p>';
                        echo '</div>';
                      }
                     
                        $loop++;
                    } ?>
                  </div>
                  
                </form>
              </div>
              
            </div>
          </div>
        </div>
    </div>
</div>
<?php $this->load->view('public/layouts/footer', $this->data); ?>