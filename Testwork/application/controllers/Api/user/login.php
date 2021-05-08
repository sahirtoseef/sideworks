<?php
$this->form_validation->set_rules('user', 'Email', 'trim|required|valid_email');
$this->form_validation->set_rules('pwd', 'Password', 'trim|required');
$this->form_validation->set_rules('role', 'User type', 'trim|required');
if ($this->form_validation->run()){
    $this->response += $this->user->login($_POST);
}else{
    $this->response['ERR'] = validation_errors();
}