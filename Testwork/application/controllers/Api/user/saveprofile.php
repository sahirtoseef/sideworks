<?php
$this->form_validation->set_rules('fname', 'Firstname', 'trim|required');
$this->form_validation->set_rules('lname', 'Lastname', 'trim|required');

$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|max_length[10]');
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
$this->form_validation->set_rules('meta[addr]', 'Address', 'trim|required|min_length[6]');
 if ($this->form_validation->run()){
    $meta = $this->input->post('meta');
    
    /*$meta = array(
     'firstname'=>$this->input->post('fname'),
     'lastname'=>$this->input->post('lname')
    );*/
    $this->response += $this->user->saveProfile($_POST, $meta);
    
}else{
    $this->response['ERR'] = validation_errors();
}