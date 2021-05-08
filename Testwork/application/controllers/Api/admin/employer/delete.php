<?php 
if($this->user->isAdmin()){
		$data = isset($_POST['params']) ? json_decode($_POST['params'], true) : false;
		$n = $this->user->deleteEmp($data);
		$this->response['msg'] = "$n Records were deleted";
}else{
	$this->response['msg'] = "Only admin can do it";
}
?>