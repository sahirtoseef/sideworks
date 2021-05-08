<?php 
if(!$this->user->isLogin() || !$this->user->isAdmin()){
	show_error('Access Denied!','403'); die();
}
$this->load->library('pdf');
switch($params){
	case 'employees':
		
			$emplist = ($this->user->isSuperAdmin()) ? (array)$this->user->getEmployeeListForExport() : (array)$this->user->getEmployeeListForExport($this->user->info->eid);
	 		$header = array('First name','Last name', 'Position', 'Employer', 'Email','Mobile', 'Address', 'City', 'State', 'Zip', 'Registration Date', 'Status');
			$table = "<table style='width:100%;border-collapse: collapse; border: 1px solid #888;'>";
			$thead = "<thead>";
			$tr = "<tr>";
			foreach($header as $h){
				$tr .= "<th style='text-align:center; border-collapse: collapse; border-bottom: 1px solid #888; border-right: 1px solid #888;'>".$h."</th>";
			}
			$tr .= "</tr>";
			$thead .= $tr."</thead>";
			$table .= $thead;
			$tbody = "<tbody>";
			
	 		foreach ($emplist as $emp){
	 			$emp = (array) $emp;
	 			unset($emp['eid']);
	 			unset($emp['ukey']);
	 			$emp['ustatus'] = userStatus($emp['ustatus']);
	 			$emp['urole'] = userRole($emp['urole']);
	 			unset($emp['ukey']);
	 			$tr = "<tr>";
	 			$val = array_values($emp);
	 			foreach($val as $v){
					$tr .= "<td  style='text-align:center; border-collapse: collapse; border-bottom: 1px solid #888; border-right: 1px solid #888;'>".$v."</td>";
				}
				$tr .= "</tr>";
				$tbody .= $tr;
	 		}
	 		$tbody .= "</tbody>";
	 		$table .= $tbody."</table>";
	 	//	echo $table;
			$this->pdf->loadHtml($table);
			$this->pdf->setPaper('A4', 'landscape');
			$this->pdf->render();
			// Output the generated PDF (1 = download and 0 = preview)
			$this->pdf->stream('employees_'.time().'.pdf', array("Attachment"=> 1));
	break;
	case 'employers':
		$filename = 'employers_'.time().'.csv'; 
 		$emplist =  (array)$this->user->getEmployerListForExport();

		// file creation 
 		$file = fopen('php://output','w');
	 		//$header = isset($emplist[0]) ? array_keys((array)$emplist[0]) : false;
	 		$header = array('Store / Employer Name', 'Store#', 'Phone', 'Address','State','City','Zip', 'Email', 'Total Employees', 'Status');
			$table = "<table style='width:100%;border-collapse: collapse; border: 1px solid #888;'>";
			$thead = "<thead>";
			$tr = "<tr>";
			foreach($header as $h){
				$tr .= "<th style='text-align:center; border-collapse: collapse; border-bottom: 1px solid #888; border-right: 1px solid #888;'>".$h."</th>";
			}
			$tr .= "</tr>";
			$thead .= $tr."</thead>";
			$table .= $thead;
			$tbody = "<tbody>";
			
	 		foreach ($emplist as $emp){
	 			$emp = (array) $emp;
	 			unset($emp['eid']);
	 			$emp['status'] = ($emp['status']) == 1 ? "Active" : "Inactive";
	 			$tr = "<tr>";
	 			$val = array_values($emp);
	 			foreach($val as $v){
					$tr .= "<td  style='text-align:center; border-collapse: collapse; border-bottom: 1px solid #888; border-right: 1px solid #888;'>".$v."</td>";
				}
				$tr .= "</tr>";
				$tbody .= $tr;
	 		}
	 		$tbody .= "</tbody>";
	 		$table .= $tbody."</table>";
	 	//	echo $table;
			$this->pdf->loadHtml($table);
			$this->pdf->setPaper('A4', 'landscape');
			$this->pdf->render();
			// Output the generated PDF (1 = download and 0 = preview)
			$this->pdf->stream('employers_'.time().'.pdf', array("Attachment"=> 1));
		
	break;
	case 'submissions':
// 	   // get dat
	 		$emplist = ($this->user->isSuperAdmin()) ? $this->form->getSubmissionsReportForExport() : $this->form->getSubmissionsReportForExport($this->user->info->eid);
	 		
// //$submissions =$this->db->get('users')->result_array();

// 		//$submission =  $this->db->select('*')->from('answerset')->join('users','users.id = answerset.Uid', 'left')->join('employers','users.empID = employers.id','left')->result_array();

// 		// file creation 
	 		$file = fopen('php://output','w');
	 		//$header = isset($emplist[0]) ? array_keys((array)$emplist[0]) : false;
	 		$header = array('First Name','Last Name', 'Position', 'Employer', 'Email', 'Mobile', 'Submission Date','Status');
			$table = "<table style='width:100%;border-collapse: collapse; border: 1px solid #888;'>";
			$thead = "<thead>";
			$tr = "<tr>";
			foreach($header as $h){
				$tr .= "<th style='text-align:center; border-collapse: collapse; border-bottom: 1px solid #888; border-right: 1px solid #888;'>".$h."</th>";
			}
			$tr .= "</tr>";
			$thead .= $tr."</thead>";
			$table .= $thead;
			$tbody = "<tbody>";
			
	 		foreach ($emplist as $emp){
	 			$emp = (array) $emp;
	 			$emp['urole'] = $emp['urole'] == 1 || $emp['urole'] == 2 ? 'Manager' : 'Staff';
	 			$emp['created'] = date('m/d/Y H:i:s',strtotime($emp['created']));
	 			$emp['Result'] = printQuestionResult($emp['Result']);

	 			$tr = "<tr>";
	 			$val = array_values($emp);
	 			foreach($val as $v){
					$tr .= "<td  style='text-align:center; border-collapse: collapse; border-bottom: 1px solid #888; border-right: 1px solid #888;'>".$v."</td>";
				}
				$tr .= "</tr>";
				$tbody .= $tr;
	 		}
	 		$tbody .= "</tbody>";
	 		$table .= $tbody."</table>";
	 	
			$this->pdf->loadHtml($table);
			$this->pdf->setPaper('A4', 'landscape');
			$this->pdf->render();
			// Output the generated PDF (1 = download and 0 = preview)
			$this->pdf->stream('employers_'.time().'.pdf', array("Attachment"=> 1));
	break;
	default:
		show_404();
	break;
}
 ?>