<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('User_model', 'user', TRUE);
		$this->load->model('Form_model', 'form', TRUE);
		//$this->load->library('pdf');
		
		$this->data = array('scriptInject'=>'');
		$this->data['user'] = $this->user;
		$this->data['form'] = $this->form;
		$this->views = dirname(__FILE__,2).'/views/';
		$this->allowed = array('login','forgotpassword','verify','not_allowed');
		$this->slug = false;
	}
	public function index($slug = 'dashboard', $params = false){
		//var_dump(in_array($slug, $this->allowed));
		//die();
		$this->slug = $slug;
		$this->validateAccess();
		if(file_exists(dirname(__FILE__,1).'/Admin/'.$slug.'.php')){
			require_once dirname(__FILE__,1).'/Admin/'.$slug.'.php';
		}
		if(method_exists($this, $slug)){
			if(!$params){
				$this->$slug();
			}else{
				$this->$slug($params);
			}
		}
         //~ echo '<pre>';print_r($this->data);die;
		if(file_exists($this->views."Admin/$slug.php")){
            
			$this->load->view('Admin/'.$slug, $this->data);
		}
		if(!file_exists($this->views."Admin/$slug.php") && !method_exists($this, $slug) && !file_exists(dirname(__FILE__,1).'/Admin/'.$slug.'.php')){
			$this->load->view('Admin/404', $this->data);
		}
	}

	
	private function validateAccess(){
		
		if(!in_array($this->slug, $this->allowed) && !$this->user->isLogin()){
				redirect('admin/login');
				die();
		}elseif(in_array($this->slug, $this->allowed) && $this->user->isAdmin()){
			redirect('admin');
				die();
		}elseif(!in_array($this->slug, $this->allowed) && $this->user->isLogin() && !$this->user->isAdmin() ){
			redirect('admin/not_allowed');
					die();
		}
		
	}

	private function AllowOnlySuper(){
		if(!$this->user->isSuperAdmin()){
			redirect('admin/not_allowed');
		}
	}
	
	public function forms(){
		$this->AllowOnlySuper();
	}
	
	public function questions(){
		$this->AllowOnlySuper();
		$this->data['id'] = (isset($_GET['id'])) ? $_GET['id'] : false;
		$this->data['questions'] = $this->form->getFormList($this->data['id']);
	}
	
	public function options(){
		$this->AllowOnlySuper();
		$this->data['id'] = (isset($_GET['id'])) ? $_GET['id'] : false;
		$this->data['question'] = $this->form->getQue($this->data['id']);
		$this->data['options'] = $this->form->getOptions($this->data['id']);
	}

	function submissions(){
	        $this->data['submissions'] = ($this->user->isSuperAdmin()) ? $this->form->getSubmissions() : $this->form->getSubmissions($this->user->info->eid);
		
	}
	
	function employees(){
        //die;
		$this->data['employees'] = ($this->user->isSuperAdmin()) ? $this->user->getEmployeeList() : $this->user->getEmployeeList($this->user->info->eid);
	}
	
	function buy_subscription(){
        //die;
		$this->data['buy_subscription'] = ($this->user->isSuperAdmin()) ? $this->user->getbillingList() : $this->user->getbillingList($this->user->info->eid);
	}
	
	
	function billing(){
        $this->data['billing'] = ($this->user->isSuperAdmin()) ? $this->user->getbillingList() : $this->user->getbillingList($this->user->info->eid);
      
	}	
	
	function cancelSubscription(){
		$result = ($this->user->cancelSubscription()) ? $this->user->cancelSubscription() : $this->user->cancelSubscription($this->user->info->eid);
		header('Location: /admin/');
		exit;
	}	
	
	public function employers(){
        //echo '<pre>';
		$this->AllowOnlySuper();
		$this->data['emplist'] = $this->user->getEmployerList();
        //print_r($this->data['emplist']);
	}
	
	public function logout(){
		$this->user->logout(base_url('admin/login'));
	}

	public function dashboard($params = false){
		$this->data['pending'] = !$this->user->isSuperAdmin() ? $this->form->getPendingSubmissions($this->user->info->eid) : $this->form->getPendingSubmissions();
		$this->data['submission'] = !$this->user->isSuperAdmin() ? $this->form->getSubmissions($this->user->info->eid) : $this->form->getSubmissions();
		$this->data['notifications'] = !$this->user->isSuperAdmin() ? $this->user->getNotifications($this->user->info->eid) : $this->user->getNotifications();
                $this->data['getStripeEmployerDetails'] = !$this->user->isSuperAdmin() ? $this->user->getEmpbyID($this->user->info->eid) : false;
                $this->data['subscriptionPopup'] = false;
                $employer = $this->user->getEmpbyID($this->user->info->eid);
                if ($this->user->isLogin() && $this->user->info->urole == "2") {
                    if (!$employer->account_subscription_status) {
                        $this->data['subscriptionPopup'] = true;
                    }
                }
		$this->data['scriptInject'] = '<script src="'.assets("admin/vendor/chart.js/Chart.min.js", true).'"></script>
		<script src="'.assets("admin/js/demo/chart-area-demo.js", true).'"></script>
		<script src="'.assets("admin/js/demo/chart-pie-demo.js", true).'"></script>';
                
	}


   public function createxlsemployer() {
        $fileName = 'Employer.xlsx';  
 // create file name
        // $fileName = 'mobile-'.time().'.xlsx';  
 // load excel library
        $this->load->library('excel');
           $data = $this->db->get('employers')->result_array();
//$objWriter = PHPExcel_IOFactory::createWriter($CI['excel'], 'Excel2007'); 
  
// print_r($data);
 //die;

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        // set Header
         $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Employer Name');
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Store');
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Phone');
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Address');
         $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'State');
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'City');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Zip');
         $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Employees');

       
       ///  $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Date');
        // $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Address');       
        // set Row       
        // set Row
        $rowCount = 2;

        foreach ($data as $val) 

// print_r($data);
// exit;

        {
           $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $val['empname']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $val['storeid']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $val['phone']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $val['addr']);
              $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $val['estate']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $val['ecity']);
          //  $objPHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $val['zip']);
           // $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val['total']);
    
           
            // $objPHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $val['date']);
            // $objPHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $val['address']);
     
            $rowCount++;
        }
 
     $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($fileName);
    // download file
        header("Content-Type: application/vnd.ms-excel");
         redirect(site_url().$fileName);              
    }
    
   
  /*
public function export_csv(){ 
		// file name 
		$filename = 'Employer_'.date('Ymd').'.csv'; 
		header("Content-Description: File Transfer"); 
		header("Content-Disposition: attachment; filename=$filename"); 
		header("Content-Type: application/csv; ");
	   // get data
//$usersData = $this->User_model->getUserDetails(); 
		 $data = $this->db->get('employers')->result_array();
		// file creation 
		$file = fopen('php://output','w');
		$header = array("Id","Name","Store#","Phone","Address","State","City","Zip","Employees"); 
		fputcsv($file, $header);
		foreach ($data as $key=>$line){ 
			fputcsv($file,$line); 
		}
		fclose($file); 
		exit; 
	}*/

function employersdata(){
	echo '$this->data["emplist"] = $this->user->getEmployerList();';
	
		echo "<hr />";
		
		echo "<hr />";
	$emplist = $this->user->getEmployerList();
	foreach($emplist as $emp){
		print_r($emp);
		echo "<hr />";
	}
}

function employeedata(){
	echo '$this->data["employees"] = $this->user->getEmployeeList();';
	
		echo "<hr />";
		
		echo "<hr />";
	$emplist = $this->user->getEmployeeList();
	foreach($emplist as $emp){
		print_r($emp);
		echo "<hr />";
	}
}

function submissiondata(){
    // die;
	echo '$this->data["submissions"] = $this->form->getSubmissions();';
	
		echo "<hr />";
		
		echo "<hr />";
	$emplist = $this->form->getSubmissions();
	foreach($emplist as $emp){
		print_r($emp);
		echo "<hr />";
	}
}

// public function export_csv_submissions(){ 
// 		// file name 
// 		$filename = 'Submission_'.date('Ymd').'.csv'; 
// 		header("Content-Description: File Transfer"); 
// 		header("Content-Disposition: attachment; filename=$filename"); 
// 		header("Content-Type: application/csv; ");
// 	   // get data
// //$submissions = $this->user->get_all_est_data(); 
// $submissions =$this->db->get('users')->result_array();

// 		//$submission =  $this->db->select('*')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->result_array();

// 		// file creation 
// 		$file = fopen('php://output','w');
// 		$header = array("First Name","Last Name","Email","Mobile","Address","City","State","Zip","Created On"); 
// 		fputcsv($file, $header);
// 		foreach ($submissions as  $key=>$line){ 
// 			fputcsv($file,$line); 
// 		}
// 		fclose($file); 
// 		exit; 
// 	}
	
		public function export_csv_submissions(){ 
	  $this->db->select("*");
    $this->db->from('users');
    $this->db->join('answerset','users.id = answerset.Uid','left');
      $this->db->join('employers','users.empID = employers.id','left');
   // $this->db->join('lead_note c', 'c.lead_id = a.id');

    $query = $this->db->get();
    $result = $query->result_array();

    if($query->num_rows() > 0){
        $delimiter = ",";
        $filename = "Submission_" . date('Y-m-d') . ".csv";

        //create a file pointer
        $f = fopen('php://memory', 'w');

        //set column headers
        $fields = array('Lead ID','Business','First Name','Last Name','Email','Personal Email','Work Number','Cell No','City','State','Zip','Date Added');

        //output each row of the data, format line as csv and write to file pointer
        foreach($result as $row){
        	
        	// print_r($result);
        	// die;

            $lineData = array($row['fname'], $row['lname'], $row['uemail'], $row['umobile'], $row['created'], $row['empname']);
            fputcsv($f, $lineData, $delimiter);
        }

        //move back to beginning of file
        fseek($f, 0);

        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        //output all remaining data on a file pointer
        fpassthru($f);
    }
    exit;
}
	
	
	
// 	public function export_csv_employees(){ 
// 		// file name 
// 		$filename = 'Employees_'.date('Ymd').'.csv'; 
// 		header("Content-Description: File Transfer"); 
// 		header("Content-Disposition: attachment; filename=$filename"); 
// 		header("Content-Type: application/csv; ");
// 	   // get dat
// 		$emplist =  (array)$this->user->getEmployeeList();
// //$submissions =$this->db->get('users')->result_array();

// 		//$submission =  $this->db->select('*')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->result_array();

// 		// file creation 
// 		$file = fopen('php://output','w');
// 		//$header = isset($emplist[0]) ? array_keys((array)$emplist[0]) : false;
// 		$header = array('id','First name','Last name','Email','mobile','Registration Date','Status','Role','Employer Name');
// 		//var_dump($header);
		
// 		//$header = array("Name","Email","Mobile","Submission Date"); 
// 		fputcsv($file, $header);
// 		foreach ($emplist as $emp){
// 			$emp = (array) $emp;
// 			unset($emp['eid']);
// 			unset($emp['ukey']);
// 			$emp['ustatus'] = userStatus($emp['ustatus']);
// 			$emp['urole'] = userRole($emp['urole']);
// 			unset($emp['ukey']);
// 			fputcsv($file,(array)$emp); 
// 		}
// 		fclose($file); 
// 		exit; 
// 	}
/*
		public function generatePDF(){
    

			$this->load->view('Admin/employers');
			$html = $this->output->get_output();
	        		// Load pdf library
			$this->load->library('pdf');
			$this->pdf->loadHtml($html);
			$this->pdf->setPaper('A4', 'landscape');
			$this->pdf->render();
			// Output the generated PDF (1 = download and 0 = preview)
			$this->pdf->stream("html_contents.pdf", array("Attachment"=> 0));		
		}
*/
		public function export_csv_employees(){ 
	  $this->db->select("*");
    $this->db->from('users');
    $this->db->join('employers','users.empID = employers.id','left');
   // $this->db->join('lead_note c', 'c.lead_id = a.id');

    $query = $this->db->get();
    $result = $query->result_array();

    if($query->num_rows() > 0){
        $delimiter = ",";
        $filename = "Employees_" . date('Y-m-d') . ".csv";

        //create a file pointer
        $f = fopen('php://memory', 'w');

        //set column headers
        $fields = array('Lead ID','Business','First Name','Last Name','Email','Personal Email','Work Number','Cell No','City','State','Zip','Date Added');

        //output each row of the data, format line as csv and write to file pointer
        foreach($result as $row){
        	
        	// print_r($result);
        	// die;

            $lineData = array($row['fname'], $row['lname'], $row['uemail'], $row['umobile'], $row['regdate'], $row['empname'], $row['addr'], $row['ecity'], $row['estate'], $row['zip']);
            fputcsv($f, $lineData, $delimiter);
        }

        //move back to beginning of file
        fseek($f, 0);

        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        //output all remaining data on a file pointer
        fpassthru($f);
    }
    exit;
}
	
 public function generatePDF(){
    

		$this->load->view('Admin/employers');
		$html = $this->output->get_output();
        		// Load pdf library
		$this->load->library('pdf');
		$this->pdf->loadHtml($html);
		$this->pdf->setPaper('A4', 'landscape');
		$this->pdf->render();
		// Output the generated PDF (1 = download and 0 = preview)
		$this->pdf->stream("html_contents.pdf", array("Attachment"=> 0));		
	}
 

}
