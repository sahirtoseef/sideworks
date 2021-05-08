<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->data['title'] = "Employers";
$emplist = $user->getEmployerList();
$this->data['modal']['content'] = '
<form class="postform" method="POST">
<div class="modal-header">
    <h5 class="modal-title" id="defaultModaltitle">Add Employer</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true"><i class="fa fa-close"></i></span>
    </button>
</div>
  <div class="modal-body">
  	<div class="form-group">
  		<input type="text" id="empname" name="empname" class="form-control" placeholder="Employer Name" />
  	</div>
  	<div class="form-group">
  		<input type="text" id="storeid" name="storeid" class="form-control" placeholder="Store ID" />
  	</div>
  	
  	<div class="form-group">
  		<input type="text" id="phone" name="phone" class="form-control" placeholder="Phone number" />
  	</div>
  	
  	<div class="form-group">
  		<textarea id="addr" name="addr" class="form-control" placeholder="Address" ></textarea>
  	</div>
  	<div class="form-group">
  		<input type="text" id="state" name="estate" class="form-control" placeholder="State" />
  	</div>
  	<div class="form-group">
  		<input type="text" id="city" name="ecity" class="form-control" placeholder="City" />
  	</div>
  	<div class="form-group">
  		<input type="text" id="zip" name="zip" class="form-control" placeholder="Zip code" />
  	</div>
  	<div class="form-group">
  		<select class="form-control" id="estatus" name="estatus">
  			<option value="1">Enabled</option>
  			<option value="0">Disabled</option>
  		</select>
  	</div>

  	<div class="form-group">
  			<div class="response"></div>
  			<input type="hidden" id="empid" name="empid" value="0" />
        <input type="hidden" id="token"  class="token" name="'.csrf('name').'" value="'.csrf().'" />
        <input type="hidden" id="action" name="action" value="admin::employer::save" />
  	</div>
  </div>
<div class="modal-footer">
  <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
  <button class="btn btn-danger" id="modalbtn" type="submit">SAVE</button>
</div>
</form>';
$this->data['scriptInject'] = "<script src='".assets("admin/vendor/datatables/jquery.dataTables.min.js", true)."'></script>
<script src='".assets("admin/vendor/datatables/dataTables.bootstrap4.min.js", true)."'></script>
<script>
// Call the dataTables jQuery plugin
  $(document).ready(function() {
    $('#dataTable').DataTable();
  });
	function empSave(ele, eid = 0){
		if(eid > 0){
			$('#defaultModaltitle').html('Update Employer'); $('#empid').val(eid); $('#empname').val(ele.parent().parent().find('.emp').text()); $('#estatus').val(ele.parent().parent().find('.emp').attr('status'));
			$('#storeid').val(ele.parent().parent().find('.storeid').text());
			$('#addr').val(ele.parent().parent().find('.addr').text());
			$('#state').val(ele.parent().parent().find('.state').text());
			$('#city').val(ele.parent().parent().find('.city').text());
			$('#zip').val(ele.parent().parent().find('.zip').text());
			$('#phone').val(ele.parent().parent().find('.phone').text());
		}else{
			$('#defaultModaltitle').html('Add Employer'); $('#empid').val(0); $('#empname').val(''); $('#estatus').val(1);	$('#storeid').val(''); $('#phone').val('');
			$('#addr').val('');
			$('#state').val('');
			$('#city').val('');
			$('#zip').val('');
		}
	}
</script>";
$this->load->view('Admin/layouts/header', $this->data); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $this->data['title']; ?></h1>
  <!--<a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="empSave($(this));"><i class="fas fa-new fa-sm text-white-50"></i>Add Employer</a>-->
</div>

<!-- Content Row -->
          <div class="row">
          	<div class="col-md-12">
          		<div class="card shadow mb-4">
		            <div class="card-header py-3">
		              <h6 class="m-0 font-weight-bold text-danger">Employers</h6>
		              
		            </div>
		            <div class="card-body">
		            	<?php if(is_null($emplist)){ ?>
		            		<div class="p-5">
		            			<div class="display-4 text-center">
			            			No Employer detail available.
			            		</div>
			            		<hr />
			            		<div class="text-center">
			            			<button class="btn btn-md btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="empSave($(this), 0);">Add Employer</button>
			            		</div>
		            		</div>
		            	<?php }else{ ?>
					<div id="invoice">
						<div class="row">
			                <div class="col-12">
			                  	<div class="text-center">
									<a href="<?php echo base_url("admin/export_csv/employers"); ?>"  class="btn btn-primary" style="margin:5px;">Export CSV/Excel</a>
									<a href="<?php echo base_url("admin/export_pdf/employers"); ?>"  class="btn btn-info" style="margin:5px;">Export PDF</a>
								</div>
								<hr />
			                </div>
		              	</div>
		              	<div  class="table-responsive">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<form class="form" style="max-width: none; width: 1005px;">
		                	<thead>
		                		<tr>
		                			<th>#</th>
		                			<th class="text-center">Employer Name</th>
		                			<th class="text-center">Store#</th>
		                			<th class="text-center">Phone</th>
		                			<th class="text-center">Address</th>
		                			<th class="text-center">State</th>
		                			<th class="text-center">City</th>
		                			<th class="text-center">Zip</th>
		                			<th class="text-center">Email</th>
		                			<th class="text-center">Employees</th>
		                			<th class="text-center">Status</th>
		                			<th class="text-right">Action</th>
		                		</tr>
		                	</thead>
		                	<tbody>
		                		<?php $i = 1; foreach($emplist as $emp){ ?>
		                		<tr>
		                			<th><?= $i; ?></th>
		                			<th class="emp text-center" status="<?= $emp->status; ?>"><?= $emp->empname; ?></th>
		                			<th class="storeid text-center"><?= $emp->storeid; ?></th>
		                			<th class="phone text-center" ><?= $emp->phone; ?></th>
		                			<th class="addr text-center" ><?= $emp->addr; ?></th>
		                			<th class="state text-center" ><?= $emp->estate; ?></th>
		                			<th class="city text-center" ><?= $emp->ecity; ?></th>
		                			<th class="zip text-center" ><?= $emp->zip; ?></th>
		                			<th class="zip text-center" ><?= $emp->email; ?></th>
		                			<th class="total text-center"><?= $emp->total; ?></th>
		                			<th class="text-center"><?= $emp->status ? '<span class="badge badge-success" title="Active"><i class="fa fa-check-circle"></i></span>' : '<span class="badge badge-danger" title="Inactive"><i class="fa fa-window-close"></i></span>'; ?></th>
		                			<th class="text-right"><button type="button" class="btn btn-sm btn-info" title="edit" data-toggle="modal" data-target="#defaultModal" onclick="empSave($(this), <?= $emp->eid; ?>);"><i class="fa fa-edit"></i></button> <button type="button" title="delete" class="btn btn-sm btn-danger xlink" xlink="admin::employer::delete" title="delete" xtoken="<?= csrf(); ?>" confirm="Are you sure to delete this employer? All submission data records will be lost related to this employer!" params='{"eid":"<?= $emp->eid; ?>"}' reload="true"><i class="fa fa-window-close"></i></button></th>
		                		</tr>
		                		<?php $i++; } ?>
		                	</tbody>
		                </table>
  </form> 
		              </div>
 </div>
		              <?php } ?>
		            </div>
		          </div>
          	</div>
          </div>
<?php $this->load->view('Admin/layouts/footer', $this->data); ?>
