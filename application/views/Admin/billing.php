<?php 

$this->data['title'] = "Blling";

$emplist = $user->getEmployerList();
//~ print_r($emplist);die;
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

$response_id = '';


$response = $this->data;

$billing_status = isset($response['user']->info->subscription_status) ? $response['user']->info->subscription_status : '';

$this->load->view('Admin/layouts/header', $this->data);
	
if(!empty($response['billing'])) {
	require_once(APPPATH.'libraries/stripe-php-master/init.php');
	$stripe = new \Stripe\StripeClient(
			  STRIPE_SECRET
			);
	$sub_id = $response['billing'][0]->subscription_id;
	
	if($sub_id != '') {
		$retrive_subscription = $stripe->subscriptions->retrieve(
			  $sub_id,
			  []
			);
	} else {
		$retrive_subscription = array();
	}
	
	//~ echo '<pre>';
	//~ print_r($retrive_subscription);
	//~ die;
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800"><?= $this->data['title']; ?></h1>
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
					  
					<?php if($billing_status == 'Active') { ?>
		
					<?php } else { ?>
						<h6 class="m-0 font-weight-bold text-danger"><?= 'You dont have an Active Subscription, Buy one!' ?></h6>
							<a href="<?php echo base_url("admin/buy_subscription"); ?>"  class="btn btn-primary" style="margin:5px;">Buy Subscription</a>
					<?php }  ?>		

<!--
					  <a href="<?php echo base_url("admin/export_csv/employees"); ?>"  class="btn btn-primary" style="margin:5px;">Export CSV/Excel</a>
					  <a href="<?php echo base_url("admin/export_pdf/employees"); ?>"  class="btn btn-info" style="margin:5px;">Export PDF</a>
-->
					</div>
                  <hr />
                </div>
              </div>

              <div class="table-responsive">
                
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" args='{"order": [[ "9", "desc" ]]}'>
                  <thead>
                    <tr>
                       <th>Name</th>
                      <th>Customer Id</th>
                      <th class="text-center">Subscription Availed</th>
                      <th class="text-center">Interval</th>
                       <th class="text-center">Price</th>
                       <th class="text-center">Currency</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Subscription Current Period End</th>
                      <th class="text-center">Cancel Subscription</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>Customer Id</th>
                      <th class="text-center">Subscription Availed</th>
                      <th class="text-center">Interval</th>
                       <th class="text-center">Price</th>
                       <th class="text-center">Currency</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Subscription Current Period End</th>
                      <th class="text-center">Cancel Subscription</th>
                    </tr>
                  </tfoot>
                  <tbody>
					  <?php if (!empty($retrive_subscription)) {?>
						 <?php foreach($retrive_subscription->items->data as $emp){ ?>
						<tr>
						  <td><?php echo $response['user']->info->fname.' '.$response['user']->info->lname; ?></td>
						  <td ><?php echo $retrive_subscription->customer; ?></td>
						  <td class="text-center empname" ><?php echo date('m/d/Y', $emp->created); ?></td>
							<td class="text-center empname" ><?php echo $emp->plan->interval; ?></td>
							<td class="text-center currency" ><?php echo $emp->price->unit_amount; ?></td>
							<td class="text-center currency" ><?php echo $emp->plan->currency; ?></td>
						   <td class="text-center addr"><?php echo $retrive_subscription->status; ?></td>
						   <td class="text-center city"><?php echo date('m/d/Y', $retrive_subscription->current_period_end); ?></td>
						   <td class="text-center city" >
							   <?php if($retrive_subscription->status != 'canceled') { ?>
								<a href="<?php echo base_url("admin/cancelSubscription"); ?>">Cancel</a>
							   <?php } else { ?>
								   <a href="<?php echo base_url("admin/buy_subscription"); ?>">Renew</a>
								<?php } ?>   
							</td>
						</tr>
						
						<?php } ?>
					<?php } else { ?>
						<td><?php echo 'No data Available'; ?></td>
					<?php 	}	?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
     
<?php $this->load->view('Admin/layouts/footer', $this->data); ?>
