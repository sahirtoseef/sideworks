<?php
if($this->user->isLogin()){
	$this->form_validation->set_rules('emp', 'Employer Name', 'trim|required');
	if ($this->form_validation->run()){
		  //$this->response = $_POST;
		  //$id = isset($_POST['empid']) ? $_POST['empid'] : false;
		 $this->response += $this->user->toEmployer($_POST['emp']);
	}else{
	    $this->response['ERR'] = validation_errors();
	}
}else{
	$this->response['ERR'] = 'Non Login Access not allowed!';
}