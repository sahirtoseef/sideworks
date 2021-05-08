<?php 
$this->data['scriptInject'] = "<script src='".assets("admin/vendor/datatables/jquery.dataTables.min.js", true)."'></script>
<script src='".assets("admin/vendor/datatables/dataTables.bootstrap4.min.js", true)."'></script>
<script>
// Call the dataTables jQuery plugin
  $(document).ready(function() {
    $('#dataTable').DataTable();
  });
</script>";
$this->load->view('Admin/layouts/header', $this->data); ?>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Submissions</h1>
          
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-danger">Submissions</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="text-center">
									  <a href="<?php echo base_url("admin/export_csv/submissions"); ?>"  class="btn btn-primary" style="margin:5px;">Export CSV/Excel</a>
									  <a href="<?php echo base_url("admin/export_pdf/submissions"); ?>"  class="btn btn-info" style="margin:5px;">Export PDF</a>
									</div>
                  <hr />
                </div>
              </div>
              <div class="table-responsive">

                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th class="text-center">Position</th>
                      <th class="text-center">Employer</th>
                      <th class="text-center">Email</th>
                      <th class="text-center">Mobile</th>
                      <th class="text-center">Submission date</th>
                      <th class="text-center">status</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th class="text-center">Position</th>
                      <th class="text-center">Employer</th>
                      <th class="text-center">Email</th>
                      <th class="text-center">Mobile</th>
                      <th class="text-center">Submission date</th>
                      <th class="text-center">status</th>
                    </tr>
                  </tfoot>
                  <tbody>
                     <?php foreach($submissions as $sub){ ?>
                    <tr>
                      <td><?= $sub->fname.' '.$sub->lname; ?></td>
                      <td class="text-center"><?= $sub->urole == 1 || $sub->urole == 2 ? 'Manager' : 'Staff'; ?></td>
                      <td class="text-center"><?= $sub->empname; ?></td>
                       <td class="text-center"><?= $sub->uemail; ?></td>
                       <td class="text-center"><?= empty($sub->umobile) ? '###_NA_###' : $sub->umobile; ?></td>
                      <td class="text-center"><?= date('m/d/Y H:i:s',strtotime($sub->created)); ?></td>
                      <td class="text-center">
                        <?php $r = (int)$sub->Result; 
                        if($r >= 40 && $r < 50){
                          echo '<span class="badge badge-warning">Medium Risk<span>';
                        }elseif($r >= 50){
                          echo '<span class="badge badge-danger">High Risk<span>';
                        }else{
                          echo '<span class="badge badge-success">Safe<span>';
                        } ?>
                        
                      </td>
                      
                    </tr>
                    
                    <?php } ?>
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>
<?php $this->load->view('Admin/layouts/footer', $this->data); ?>