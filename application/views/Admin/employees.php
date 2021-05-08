<?php 
$this->data['title'] = "Employees";

$emplist = $user->getEmployerList();
  $emps = '<div class="form-group"><input list="employers" type="text" class="form-control rounded-pill" id="empname" placeholder="Type name of employer and select" name="empname" value="" required /></div>';
if($user->isSuperAdmin()){           
      $empsdata = '<datalist id="employers" style="display:none;">';
    	foreach($emplist as $emp){
    		$empsdata .= "<option value='".$emp->empname."'>".$emp->storeid .' - '.$emp->estate."</option>";
    	}
      $empsdata .= '</datalist>';
}else{
  $empsdata = '<datalist id="employers" style="display:none;">';
  $empsdata .= "<option value='".$user->info->employer."'></option>";
   $empsdata .= '</datalist>'; 
}
$super = $user->isSuperAdmin() ? '<option value="1">Super Admin</option>' : '';
$this->data['modal']['class'] = "modal-dialog modal-dialog-centered modal-lg";
$this->data['modal']['content'] = '
<form id="saveemp" class="postform" method="POST">
<div class="modal-header">
    <h5 class="modal-title" id="defaultModaltitle">Add Employee</h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true"><i class="fa fa-close"></i></span>
    </button>
</div>
  <div class="modal-body">
    '.$emps.$empsdata.'
  	<div class="form-group">
  	    <label for="fname">First Name</label>
        <input type="text" class="form-control" id="fname" placeholder="First Name" name="fname" required value="" />
    </div>
    <div class="form-group">
      <label for="lname">Last Name</label>
      <input type="text" class="form-control" id="lname" placeholder="Last Name" name="lname" required value="" />
    </div>
    
    <div class="form-group">
      <label for="uemail">Email</label>
      <input type="email" class="form-control" id="uemail" placeholder="Email" name="email" required value="" />
    </div>
    <div class="form-group">
      <label for="umobile">Mobile</label>
      <input type="text" class="form-control" id="umobile" placeholder="Mobile Number" name="mobile" required value="" />
    </div>
    <div class="form-group">
      <label for="meta">Address</label>
      <textarea class="form-control" id="addr" placeholder="Address" name="meta[addr]" required ></textarea>
    </div>
    <div class="form-group">
      <input type="text" class="form-control rounded-pill" id="city" placeholder="Name of the city" name="meta[city]" required value="" />
    </div>
    <div class="form-group">
      <input type="text" class="form-control rounded-pill" id="state" placeholder="Name of the state" name="meta[state]" required value="" />
    </div>
    <div class="form-group">
      <input type="text" class="form-control rounded-pill" id="zip" placeholder="Zip Code" name="meta[zip]" required value="" />
    </div>
  	<div class="form-group">
  	  <label for="role">Role</label>
  		<select class="form-control" id="role" name="role" required>
  		  <option value="">Select Role</option>
  		  '.$super.'
  			<option value="2">Employer / Manager</option>
  			<option value="3">Employee / Staff</option>
  		</select>
  	</div>
    <div class="form-group">
      <label for="status">Status</label>
  		<select class="form-control" id="status" name="status">
  		  <option value="0">Not Verfied</option>
  			<option value="1">Verified</option>
  			<option value="2">Disabled</option>
  		</select>
  	</div>
  	<div class="form-group">
  			<div class="response"></div>
                        <input type="hidden" id="umobile-hidden" name="mobile-hidden" value="1" />
  			<input type="hidden" id="uid" name="uid" value="0" />
        <input type="hidden" id="token" class="token" name="'.csrf('name').'" value="'.csrf().'" />
        <input type="hidden" id="action" name="action" value="admin::employee::save" />
  	</div>
  </div>
<div class="modal-footer">
  <button class="btn btn-sm btn-secondary" type="button" data-dismiss="modal">Close</button>
  <button class="btn btn-sm btn-danger" id="modalbtn btn-sm" type="submit">SAVE</button>
</div>
</form>';
$this->data['scriptInject'] = "
<script src='".assets("admin/vendor/datatables/jquery.dataTables.min.js", true)."'></script>
<script src='".assets("admin/vendor/datatables/dataTables.bootstrap4.min.js", true)."'></script>
<script>
  // Call the dataTables jQuery plugin
  $(document).ready(function() {
    var attr = $('#dataTable').attr('args') !== undefined ? $.parseJSON($('#dataTable').attr('args')) : {};
    $('#dataTable').DataTable(attr);
  });

	function empSave(ele){
	  var id = ele.parent().parent().attr('uid');
	  //console.log(id);
	  if(id === undefined){
	    $('#defaultModaltitle').html('Add Employee'); 
			$('#empid').val(0);
			$('#uid').val(0);
			$('#saveemp').get(0).reset();
	  }else{
	   	$('#defaultModaltitle').html('Update Employee');
	   	console.log(ele.parent().parent().attr('role'));
	   	$('#empname').val(ele.parent().parent().find('.empname').text());
	   	$('#role').val(ele.parent().parent().attr('urole'));
	   	$('#status').val(ele.parent().parent().attr('status'));
	   	$('#fname').val(ele.parent().parent().attr('fname'));
	   	$('#lname').val(ele.parent().parent().attr('lname'));
	   	$('#uemail').val(ele.parent().parent().find('.uemail').text());
	   	$('#umobile').val(ele.parent().parent().find('.umobile').text());
	   	$('#addr').val(ele.parent().parent().find('.addr').text());
	   	$('#city').val(ele.parent().parent().find('.city').text());
	   	$('#state').val(ele.parent().parent().find('.state').text());
	   	$('#zip').val(ele.parent().parent().find('.zip').text());
			$('#empid').val(id); 
			$('#uid').val(ele.parent().parent().attr('uid'));
	  }
	}
</script>";
$this->load->view('Admin/layouts/header', $this->data); ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $this->data['title']; ?></h1>
  <?php //if ($this->user->isSuperAdmin()) { ?>
  <!-- <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal" data-target="#defaultModal" onclick="empSave($(this));"><i class="fas fa-new fa-sm text-white-50"></i>Add Employee</a> -->
  <?php //} ?>
</div>
          
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-danger"><?= $this->data['title']; ?></h6>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="text-center">
									  <a href="<?php echo base_url("admin/export_csv/employees"); ?>"  class="btn btn-primary" style="margin:5px;">Export CSV/Excel</a>
									  <a href="<?php echo base_url("admin/export_pdf/employees"); ?>"  class="btn btn-info" style="margin:5px;">Export PDF</a>
									 
									</div>
                  <hr />
                </div>
              </div>

              <div class="table-responsive">
                
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" args='{"order": [[ "9", "desc" ]]}'>
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th class="text-center">Position</th>
                      <th class="text-center">Employer</th>
                      <th class="text-center">Email</th>
                      <th class="text-center">Mobile</th>
                      
                      <th class="text-center">Address</th>
                      <th class="text-center">City</th>
                      <th class="text-center">State</th>
                      <th class="text-center">Zip</th>
                      <th class="text-center">Created on</th>
                      <th class="text-center">Status</th>
                      <th class="text-right">Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th class="text-center">Position</th>
                      <th class="text-center">Employer</th>
                      <th class="text-center">Email</th>
                      <th class="text-center">Mobile</th>
                      
                      <th class="text-center">Address</th>
                       <th class="text-center">City</th>
                      <th class="text-center">State</th>
                      <th class="text-center">Zip
                      <th class="text-center">Created on</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </tfoot>
                  <tbody>
                     <?php foreach($employees as $emp){ ?>
                    <tr uid="<?= $emp->userid; ?>" status="<?= $emp->ustatus; ?>" urole="<?= $emp->urole; ?>" fname='<?= $emp->fname; ?>' lname='<?= $emp->lname; ?>'>
                      <td><?= $emp->fname.' '.$emp->lname; ?></td>
                      <td class="text-center" class=''><?= userRole($emp->urole); ?></td>
                      <td class="text-center empname" ><?= $emp->empname; ?></td>
                       <td class="text-center uemail" ><?= $emp->uemail; ?></td>
                       <td class="text-center umobile"><?= empty($emp->umobile) ? '' : $emp->umobile; ?></td>
                       <td class="text-center addr"><?= $user->get_meta('addr', $emp->userid); ?></td>
                       <td class="text-center city"><?= $user->get_meta('city', $emp->userid); ?></td>
                       <td class="text-center state"><?= $user->get_meta('state', $emp->userid); ?></td>
                       <td class="text-center zip"><?= $user->get_meta('zip', $emp->userid); ?></td>
                      <td class="text-center"><?= date('m/d/Y H:i:s',strtotime($emp->regdate)); ?></td>
                      <td class="text-center">
                        <?php 
                        if($emp->ustatus == 0){
                          echo '<span class="badge badge-warning">Not Verified<span>';
                        }elseif($emp->ustatus == 1){
                          echo '<span class="badge badge-success">Verified<span>';
                        }else{
                          echo '<span class="badge badge-danger">Ban<span>';
                        } ?>
                      </td>
                      <td class="text-right"><button type="button" class="btn btn-sm  btn-info" data-toggle="modal" data-target="#defaultModal" onclick="empSave($(this));" title="Edit"><i class="fa fa-edit"></i></button><div class="p-1" ></div><button  type="button" title="delete" class="btn btn-sm btn-danger xlink" xlink="admin::employee::delete" title="delete" xtoken="<?= csrf(); ?>" confirm="Are you sure to delete this employer? All submission data records will be lost related to this user!" params='{"eid":"<?= $emp->userid; ?>"}' reload="true"><i class="fa fa-window-close"></i></button></td>
                    </tr>
                    
                    <?php } ?>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
     
<?php $this->load->view('Admin/layouts/footer', $this->data); ?>
