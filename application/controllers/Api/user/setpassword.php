<?php 
$this->form_validation->set_rules('utoken', 'User Token', 'trim|required');
$this->form_validation->set_rules('pwd', 'Password', 'trim|required|min_length[6]');
$this->form_validation->set_rules('cpwd', 'Password Confirmation', 'trim|required|matches[pwd]');
if ($this->form_validation->run()){
	$token = decrypt($_POST['utoken'], 'X');
	if($token){
		$this->response += $this->user->resetpassword($_POST['pwd'],$token);
	}else{
		 $this->response['ERR'] = 'INVALID_USER_TOKEN';
	}
}else{
	 $this->response['ERR'] = validation_errors();
}