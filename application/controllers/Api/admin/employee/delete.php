<?php 
if($this->user->isSuperAdmin()){
		$data = isset($_POST['params']) ? json_decode($_POST['params'], true) : false;
		$n = $this->user->delete($data);
		$this->response['msg'] = "$n Records were deleted";
}else{
	$this->response['msg'] = "Only admin can do it";
}
?>