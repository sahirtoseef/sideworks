<?php 
if($this->user->isSuperAdmin()){
		$data = isset($_POST['params']) ? json_decode($_POST['params'], true) : false;
		$n = $this->form->deleteopt($data);
		$this->response['msg'] = "$n Records were deleted";
}
?>