<?php 
$this->data['title'] = "Forms";
$forms = $form->getList();
$this->data['modal']['content'] = '
<form class="postform" method="POST">
<div class="modal-header">
    <h5 class="modal-title" id="defaultModaltitle">Create Form</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true"><i class="fa fa-close"></i></span>
    </button>
</div>
  <div class="modal-body">
  	<div class="form-group">
  		<input type="text" id="fname" name="fname" class="form-control" placeholder="Give a name to the form" />
  	</div>
  	<div class="form-group">
  			<div class="response"></div>
  			<input type="hidden" id="fid" name="fid" value="0" />
        <input type="hidden" id="token" class="token" name="'.csrf('name').'" value="'.csrf().'" />
        <input type="hidden" id="action" name="action" value="admin::form::save" />
  	</div>
  </div>
<div class="modal-footer">
  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
  <button class="btn btn-danger" id="modalbtn" type="submit">SAVE</button>
</div>
</form>';
$this->data['scriptInject'] = "<script>
	function formSave(ele, fid = 0){
		if(fid > 0){
			$('#defaultModaltitle').html('Update Form'); $('#fid').val(fid); $('#fname').val(ele.parent().parent().find('.form-label').text());
		}else{
			$('#defaultModaltitle').html('Create Form'); $('#fid').val(0); $('#fname').val(''); 
		}
	}
</script>";
$this->load->view('Admin/layouts/header', $this->data); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $this->data['title']; ?></h1>
  <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), 0);"><i class="fas fa-edit fa-sm text-white-50"></i> Add New Form</a>
  
</div>
  <!-- Content Row -->
          <div class="row">
          	<div class="col-md-12">
          		<div class="card shadow mb-4">
		            <div class="card-header py-3">
		              <h6 class="m-0 font-weight-bold text-danger">Forms</h6>
		              
		            </div>
		            <div class="card-body">
		            	<?php if(is_null($forms) || empty($forms)){ ?>
		            		<div class="p-5">
		            			<div class="display-4 text-center">
			            			No Form is created.
			            		</div>
			            		<hr />
			            		<div class="text-center">
			            			<button class="btn btn-md btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), 0);">Create a Form</button>
			            		</div>
		            		</div>
		            	<?php }else{ ?>
		              <div class="table-responsive">
		                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		                	<thead>
		                		<tr>
		                			<th>#</th>
		                			<th class="text-center">Label</th>
		                			<th class="text-center">Questions</th>
		                			<th class="text-center">Submissions</th>
		                			<th class="text-right">Action</th>
		                		</tr>
		                	</thead>
		                	<tbody>
		                		<?php $i = 1; foreach($forms as $form){ ?>
		                		<tr>
		                			<th><?= $i; ?></th>
		                			<th class="form-label text-center" status="<?= $form->QsLabel; ?>"><?= $form->QsLabel; ?></th>
		                			<th class="total text-center"><?= $form->questions; ?><br /><a href="<?= base_url('admin/questions?id='.$form->id); ?>" class="btn btn-sm btn-success text-white" >Manage Questions</a></th>
		                			<th class="text-center"><?= $form->submissions; ?><br /><a href="<?= base_url('admin/submissions?id='.$form->id); ?>" class="btn btn-sm btn-success text-white" >View Submissions</a></th>
		                			<th class="text-right">
		                			  <button type="button" class="btn btn-sm btn-info" title="edit" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), <?= $form->id; ?>);"><i class="fa fa-edit"></i></button>
		                			  <button type="button" class="btn btn-sm btn-danger xlink" xlink="admin::form::delete" title="delete" xtoken="<?= csrf(); ?>" confirm="Are you sure to delete this form? All submission data records will be lost!" params='{"fid":"<?= $form->id; ?>"}' reload="true"><i class="fa fa-window-close"></i></button>
		                			  
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