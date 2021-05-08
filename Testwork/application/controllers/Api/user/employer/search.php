<?php
if($this->user->isLogin()){
	$this->form_validation->set_rules('empname', 'Employer Name', 'trim');
	$this->form_validation->set_rules('city_or_state', 'City or State', 'trim');
	if ($this->form_validation->run()){
	  	// $this->response += 'success';
	  	$this->response += $this->user->searchEmployer($_POST);
	}else{
	    $this->response['ERR'] = validation_errors();
	}
}else{
	$this->response['ERR'] = 'Non Login Access not allowed!';
}
