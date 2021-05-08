<?php 
$this->data['title'] = "Options";
$this->data['modal']['content'] = '
<form class="postform" method="POST">
<div class="modal-header">
    <h5 class="modal-title" id="defaultModaltitle">Add Question</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true"><i class="fa fa-close"></i></span>
    </button>
</div>
  <div class="modal-body">
  	<div class="form-group">
  		<input type="text" id="label" name="label" class="form-control" required placeholder="Type option value here" />
  	</div>
  	
  	<div class="form-group">
  		<input type="number" id="points" name="points" class="form-control" required placeholder="Points" />
  	</div>
  	
  	<div class="form-group">
  			<div class="response"></div>
  			<input type="hidden" id="qid" name="qid" value="'.$id.'" />
  			<input type="hidden" id="oid" name="oid" value="0" />
        <input type="hidden" id="token" class="token" name="'.csrf('name').'" value="'.csrf().'" />
        <input type="hidden" id="action" name="action" value="admin::form::saveopt" />
  	</div>
  </div>
<div class="modal-footer">
  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
  <button class="btn btn-danger" id="modalbtn" type="submit">SAVE</button>
</div>
</form>';
$this->data['scriptInject'] = "<script>
	function formSave(ele, oid = 0){
		if(oid > 0){
			$('#defaultModaltitle').html('Update Option'); $('#oid').val(oid); $('#label').val(ele.parent().parent().find('.form-label').text()); $('#points').val(ele.parent().parent().find('.points').text()); 
		}else{
			$('#defaultModaltitle').html('Add Option'); $('#oid').val(0); $('#label').val('');  $('#points').val(0); 
		}
	}
</script>";
$this->load->view('Admin/layouts/header', $this->data); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $this->data['title']; ?></h1>
  <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), 0);"><i class="fas fa-edit fa-sm text-white-50"></i> Add Option</a>
  
</div>
  <!-- Content Row -->
          <div class="row">
          	<div class="col-md-12">
          		<div class="card shadow mb-4">
		            <div class="card-header py-3">
		              <h6 class="m-0 font-weight-bold text-danger"><?= $question->QLabel; ?></h6>
		              
		            </div>
		            <div class="card-body">
		            	<?php if(is_null($options) || empty($options)){ ?>
		            		<div class="p-5">
		            			<div class="display-4 text-center">
			            			No Option is created for this quetion.
			            		</div>
			            		<hr />
			            		<div class="text-center">
			            			<button class="btn btn-md btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), 0);">Create an option</button>
			            		</div>
		            		</div>
		            	<?php }else{ ?>
		              <div class="table-responsive">
		                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		                	<thead>
		                		<tr>
		                			<th>#</th>
		                			<th class="text-center">Option</th>
		                			<th class="text-center">Points</th>
		                			<th class="text-right">Action</th>
		                		</tr>
		                	</thead>
		                	<tbody>
		                		<?php $i = 1; foreach($options as $opt){ ?>
		                		<tr>
		                			<th><?= $i; ?></th>
		                			<th class="form-label text-center"><?= $opt->OpLabel; ?></th>
		                			<th class="points text-center" qtype=""><?= $opt->quepoints; ?></th>
		                			
		                			<th class="text-right">
		                			  <button type="button" class="btn btn-sm btn-info" title="edit" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), <?= $opt->QOID; ?>);"><i class="fa fa-edit"></i></button>
		                			  <button type="button" class="btn btn-sm btn-danger xlink" xlink="admin::form::deleteopt" title="delete" xtoken="<?= csrf(); ?>" confirm="Are you sure to delete this option?" params='{"oid":"<?= $opt->QOID; ?>"}' reload="true"><i class="fa fa-window-close"></i></button>
		                			  
		                			</th>
		                		</tr>
		                		<?php $i++; } ?>
		                	</tbody>
		                </table>
		              </div>
		              <?php } ?>
		            </div>
		          </div>
          	</div>
          </div>
<!-- Content Row -->
  
<?php $this->load->view('Admin/layouts/footer', $this->data); ?>