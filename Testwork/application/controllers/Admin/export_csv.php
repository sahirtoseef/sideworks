<?php 
if(!$this->user->isLogin() || !$this->user->isAdmin()){
	show_error('Access Denied!','403'); die();
}

switch($params){
	case 'employees':
			$filename = 'employees_'.time().'.csv'; 
			header("Content-Description: File Transfer"); 
	 		header("Content-Disposition: attachment; filename=$filename"); 
	 		header("Content-Type: application/csv; ");
// 	   // get dat
			$emplist = ($this->user->isSuperAdmin()) ? (array)$this->user->getEmployeeListForExport() : (array)$this->user->getEmployeeListForExport($this->user->info->eid);
// //$submissions =$this->db->get('users')->result_array();

// 		//$submission =  $this->db->select('*')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->result_array();

// 		// file creation 
	 		$file = fopen('php://output','w');
	 		//$header = isset($emplist[0]) ? array_keys((array)$emplist[0]) : false;
	 		$header = array('First name','Last name', 'Position', 'Employer', 'Email','Mobile', 'Address', 'City', 'State', 'Zip', 'Registration Date', 'Status');
			//var_dump($header);
		
// 		//$header = array("Name","Email","Mobile","Submission Date"); 
	 		fputcsv($file, $header);
	 		foreach ($emplist as $emp){
	 			$emp = (array) $emp;
	 			$emp['ustatus'] = userStatus($emp['ustatus']);
	 			$emp['urole'] = userRole($emp['urole']);
	 			fputcsv($file,(array)$emp); 
	 		}
	 		fclose($file); 
	 		exit;
	break;
	case 'employers':
		$filename = 'employers_'.time().'.csv'; 
			header("Content-Description: File Transfer"); 
	 		header("Content-Disposition: attachment; filename=$filename"); 
	 		header("Content-Type: application/csv; ");
// 	   // get dat
	 		$emplist =  (array)$this->user->getEmployerListForExport();
// //$submissions =$this->db->get('users')->result_array();

// 		//$submission =  $this->db->select('*')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->result_array();

// 		// file creation 
	 		$file = fopen('php://output','w');
	 		//$header = isset($emplist[0]) ? array_keys((array)$emplist[0]) : false;
	 		$header = array('Store / Employer Name', 'Store#', 'Phone', 'Address','State','City','Zip', 'Email', 'Total Employees', 'Status');
			//var_dump($header);
		
// 		//$header = array("Name","Email","Mobile","Submission Date"); 
	 		fputcsv($file, $header);
	 		foreach ($emplist as $emp){
	 			$emp = (array) $emp;
	 			unset($emp['eid']);
	 			$emp['status'] = ($emp['status']) == 1 ? "Active" : "Inactive";
	 			
	 			fputcsv($file,(array)$emp); 
	 		}
	 		fclose($file); 
	 		exit;
		
	break;
	case 'submissions':
		$filename = 'submissions_'.time().'.csv'; 
		header("Content-Description: File Transfer"); 
	 	header("Content-Disposition: attachment; filename=$filename"); 
	 	header("Content-Type: application/csv; ");
// 	   // get dat
	 		$emplist = ($this->user->isSuperAdmin()) ? $this->form->getSubmissionsReportForExport() : $this->form->getSubmissionsReportForExport($this->user->info->eid);
	 		
// //$submissions =$this->db->get('users')->result_array();

// 		//$submission =  $this->db->select('*')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->result_array();

// 		// file creation 
	 		$file = fopen('php://output','w');
	 		//$header = isset($emplist[0]) ? array_keys((array)$emplist[0]) : false;
	 		$header = array('First Name','Last Name', 'Position', 'Employer', 'Email', 'Mobile', 'Submission Date','Status');
			//var_dump($header);
		
// 		//$header = array("Name","Email","Mobile","Submission Date"); 
	 		fputcsv($file, $header);
	 		foreach ($emplist as $emp){
	 			$emp = (array) $emp;
	 			$emp['urole'] = $emp['urole'] == 1 || $emp['urole'] == 2 ? 'Manager' : 'Staff';
	 			$emp['created'] = date('m/d/Y H:i:s',strtotime($emp['created']));
	 			$emp['Result'] = printQuestionResult($emp['Result']);
	 			fputcsv($file,(array)$emp); 
	 		}
	 		fclose($file); 
	 		exit;
	break;
	default:
		show_404();
	break;
}
 ?>