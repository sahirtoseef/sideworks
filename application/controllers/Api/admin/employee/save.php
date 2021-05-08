<?php
if(!$this->user->isAdmin()){
	$this->response['ERR'] = 'You are not allowed to do this';
	die();
}
$this->form_validation->set_rules('fname', 'Firstname', 'trim|required');
$this->form_validation->set_rules('lname', 'Lastname', 'trim|required');
$this->form_validation->set_rules('uid', 'User ID', 'trim|required|numeric');
$this->form_validation->set_rules('empname', 'Employee Name', 'trim|required');
$this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|max_length[10]');
$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
 if ($this->form_validation->run()){
 		$id = $this->input->post('uid');
    $meta = $this->input->post('meta');
    $emp = $this->user->getEmp($_POST['empname']);
    $_POST['empid'] = !is_null($emp) ? $emp->id : 0;
    
    if($id > 0 && !is_null($id)){
    	$response = $this->user->saveProfile($_POST, $meta, $id);
    	$this->response += $response;
    }else{
    $_POST['pwd'] = randomstring();
    $response = $this->user->register($_POST, $meta);
    
    if(array_keys($response)[0] != 'ERR'){
				$htmlContent = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
				  <tbody>
				    <tr>
				      <td>
				        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%;max-width:600px;font-family: Arial, sans-serif" align="center">
				          <tbody>
				            <tr>
				              <td style="padding:0px 0px 0px 0px;color:#000000;text-align:left" bgcolor="#ffffff" width="100%" align="center">                                
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td bgcolor="#a50101" align="center">
				                        <p style="color: #fff; padding: 50px; font-size: 30px; text-transform: uppercase;letter-spacing: 2px;">well pass</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td align="left" style="padding: 20px">
				                        <p style="color: #000;font-size: 18px; text-align:cemter;">Hi, '.$_POST['fname'].'!</p>
				                        <p style="color: #000;font-size: 14px;line-height: 22px">Your account has been created on well pass &amp; your password is '.$_POST['pwd'].'</p>
				                        <p style="color: #000;font-size: 14px;line-height: 22px">Please verify your email address to access well pass.</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td align="center" style="padding: 20px">
				                        <a href="'.$this->user->verificationLink($_POST['email']).'" style="background-color: #a50101; color: #fff; display: inline-block;font-size: 16px;border-radius: 50px; text-decoration: none;text-transform: uppercase;padding: 20px 30px">Verify Now</a>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td align="left" style="padding: 20px">
				                        <p style="color: #000;font-size: 14px;line-height: 22px">Click above button or click this link or copy & paste link given below</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td align="center" style="padding: 20px 20px 40px;">
				                        <a href="'.$this->user->verificationLink($_POST['email']).'" style="background-color: #099e41; color: #fff; display: inline-block;font-size: 16px;border-radius: 50px; text-decoration: none;padding: 20px 30px">'.$this->user->verificationLink($_POST['email']).'</a>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td bgcolor="#f1f1f1" align="left" style="padding: 30px 30px 20px;font-size: 14px;line-height: 20px">
				                        <b>Note : </b>
				                        <ul style="padding-left: 20px">
				                          <li>Stay safe, Stay home</li>
				                          <li>Keep social distance</li>
				                          <li>Wash hands regularly</li>
				                          <li>Wear mask</li>
				                        </ul>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td bgcolor="#a50101" align="center">
				                        <p style="color: #fff; padding: 30px; font-size: 20px;">Stay Safe, Stay Home</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				              </td>
				            </tr>
				          </tbody>
				        </table>
				      </td>
				    </tr>
				  </tbody>
				</table>';
		    
		  $config['mailtype'] = 'html';
		  $this->email->initialize($config);
		  $this->email->to($_POST['email']);
		  $this->email->from('web@dreamzlive.com','Well Ness');
		  $this->email->subject('WellPass Account Verification');
		  $this->email->message($htmlContent);
		  if($this->email->send()){
		  	$this->response['REL'] = 'New user created and email sent!';
		  }
			}else{
			  $this->response += $response;
			}
    }
   
 }else{
    $this->response['ERR'] = validation_errors();
 }