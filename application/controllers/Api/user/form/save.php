<?php 
if($this->user->isLogin()){
$qsid = decrypt($this->input->post('qs'),'X');
$qa = $this->input->post('QA');
$qsparam = array(
	'QsID'=>$qsid,
	'Uid'=>$this->user->id,
	'Result'=>$this->input->post('points')
);

$this->response = $this->form->submit($qsparam, $qa);
} ?>