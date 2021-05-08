<?php
if($this->user->isSuperAdmin()){
	$this->form_validation->set_rules('fname', 'Form Name', 'trim|required');
	if ($this->form_validation->run()){
			$id = isset($_POST['fid']) && !is_int($_POST['fid']) ? $_POST['fid'] : false; 
	    $this->response += $this->form->create($_POST['fname'], $id);
	}else{
	    $this->response['ERR'] = validation_errors();
	}
}else{
	$this->response['ERR'] = 'Non Super Access is not allowed!';
}