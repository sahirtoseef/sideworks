<?php

if($this->user->isSuperAdmin()){
	$this->form_validation->set_rules('label', 'Question', 'trim|required');
	$this->form_validation->set_rules('points', 'Question Type', 'trim|required');
	$this->form_validation->set_rules('oid', 'Option ID', 'trim|required');
	$this->form_validation->set_rules('qid', 'Question ID', 'trim|required');
	if ($this->form_validation->run()){
		$params = array(
			'Qid'=>$this->input->post('qid'),
			'QOID'=>$this->input->post('oid'),
			'OpLabel'=>$this->input->post('label'),
			'quepoints'=>$this->input->post('points')
		);
		$this->response += $this->form->saveopt($params);
	}else{
		$this->response['ERR'] = validation_errors();
	}
	
}else{
	$this->response['ERR'] = 'Non Super Access is not allowed!';
}