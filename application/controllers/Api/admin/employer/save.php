<?php
if($this->user->isAdmin()){
	$this->form_validation->set_rules('empname', 'Employer Name', 'trim|required');
	if ($this->form_validation->run()){
		  //$this->response = $_POST;
		  $id = isset($_POST['empid']) ? $_POST['empid'] : false;
	    $this->response += $this->user->saveEmp($_POST, $id);
	}else{
	    $this->response['ERR'] = validation_errors();
	}
}else{
	$this->response['ERR'] = 'Non Admin Access not allowed!';
}