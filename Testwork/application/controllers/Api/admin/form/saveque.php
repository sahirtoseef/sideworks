<?php

if($this->user->isSuperAdmin()){
	$this->form_validation->set_rules('qname', 'Question', 'trim|required');
	$this->form_validation->set_rules('qtype', 'Question Type', 'trim|required');
	$this->form_validation->set_rules('fid', 'Form ID', 'trim|required');
	$this->form_validation->set_rules('qid', 'Question ID', 'trim|required');
	if ($this->form_validation->run()){
		$params = array(
			'Qid'=>$this->input->post('qid'),
			'QsID'=>$this->input->post('fid'),
			'QLabel'=>$this->input->post('qname'),
			'Qtype'=>$this->input->post('qtype')
		);
		$this->response += $this->form->saveQue($params);
	}else{
		$this->response['ERR'] = validation_errors();
	}
	
}else{
	$this->response['ERR'] = 'Non Super Access is not allowed!';
}