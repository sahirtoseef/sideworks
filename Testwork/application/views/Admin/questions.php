<?php 
$this->data['title'] = "Questions";
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
  		<input type="text" id="qname" name="qname" class="form-control" required placeholder="Type question here" />
  	</div>
  	
  	<div class="form-group">
  		<div class="custom-control custom-radio custom-control-inline">
			  <input type="radio" id="qtype1" name="qtype" class="qtype custom-control-input" value="0" required>
			  <label class="custom-control-label" for="qtype1">Single</label>
			</div>
			<div class="custom-control custom-radio custom-control-inline">
			  <input type="radio" id="qtype2" name="qtype" class="qtype custom-control-input" value="1" required>
			  <label class="custom-control-label" for="qtype2">Multiple</label>
			</div>
			<div class="custom-control custom-radio custom-control-inline">
			  <input type="radio" id="qtype3" name="qtype" class="qtype custom-control-input" value="2" required>
			  <label class="custom-control-label" for="qtype3">Text</label>
			</div>
  	</div>
  	<div class="form-group">
  			<div class="response"></div>
  			<input type="hidden" id="fid" name="fid" value="'.$id.'" />
  			<input type="hidden" id="qid" name="qid" value="0" />
        <input type="hidden" id="token" class="token" name="'.csrf('name').'" value="'.csrf().'" />
        <input type="hidden" id="action" name="action" value="admin::form::saveque" />
  	</div>
  </div>
<div class="modal-footer">
  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
  <button class="btn btn-danger" id="modalbtn" type="submit">SAVE</button>
</div>
</form>';
$this->data['scriptInject'] = "<script>
	function formSave(ele, qid = 0, qtype = 0){
		if(qid > 0){
			$('#defaultModaltitle').html('Update Question'); $('#qid').val(qid); $('#qname').val(ele.parent().parent().find('.form-label').text()); $('input[name=qtype][value=' + qtype + ']').prop('checked', true);
		}else{
			$('#defaultModaltitle').html('Add Question'); $('#qid').val(0); $('#qname').val('');  $('input[name=qtype]').prop('checked', false);
		}
	}
</script>";
$this->load->view('Admin/layouts/header', $this->data); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $this->data['title']; ?></h1>
  <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), 0);"><i class="fas fa-edit fa-sm text-white-50"></i> Add Question</a>
  
</div>
  <!-- Content Row -->
          <div class="row">
          	<div class="col-md-12">
          		<div class="card shadow mb-4">
		            <div class="card-header py-3">
		              <h6 class="m-0 font-weight-bold text-danger">Questions</h6>
		              
		            </div>
		            <div class="card-body">
		            	<?php if(is_null($questions) || empty($questions)){ ?>
		            		<div class="p-5">
		            			<div class="display-4 text-center">
			            			No Question is created for this form.
			            		</div>
			            		<hr />
			            		<div class="text-center">
			            			<button class="btn btn-md btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), 0);">Create a question</button>
			            		</div>
		            		</div>
		            	<?php }else{ ?>
		              <div class="table-responsive">
		                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		                	<thead>
		                		<tr>
		                			<th>#</th>
		                			<th class="text-center">Question</th>
		                			<th class="text-center">Type</th>
		                			<th class="text-center">Options</th>
		                			<th class="text-right">Action</th>
		                		</tr>
		                	</thead>
		                	<tbody>
		                		<?php $i = 1; foreach($questions as $que){ ?>
		                		<tr>
		                			<th><?= $i; ?></th>
		                			<th class="form-label text-center"><?= $que->QLabel; ?></th>
		                			<th class="ftype text-center" qtype=""><?= quetype($que->Qtype); ?></th>
		                			<th class="text-center"><?= $que->options; ?><br /><a href="<?= base_url('admin/options?id='.$que->id); ?>" class="btn btn-sm btn-success text-white" >Manage Options</a></th>
		                			<th class="text-right">
		                			  <button type="button" class="btn btn-sm btn-info" title="edit" data-toggle="modal" data-target="#defaultModal" onclick="formSave($(this), <?= $que->id; ?>, <?= $que->Qtype; ?>);"><i class="fa fa-edit"></i></button>
		                			  <button type="button" class="btn btn-sm btn-danger xlink" xlink="admin::form::deleteque" title="delete" xtoken="<?= csrf(); ?>" confirm="Are you sure to delete this question?" params='{"qid":"<?= $que->id; ?>"}' reload="true"><i class="fa fa-window-close"></i></button>
		                			  
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