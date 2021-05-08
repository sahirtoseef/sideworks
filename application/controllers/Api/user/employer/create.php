<?php

$this->form_validation->set_rules('emp', 'Employer Name', 'trim|required');
//$this->form_validation->set_rules('storeid', 'Store ID', 'trim');
// $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
// New Start
$this->form_validation->set_rules('phone1', 'Phone 1', 'trim|required|numeric|max_length[3]');
$this->form_validation->set_rules('phone2', 'Phone 2', 'trim|required|numeric|max_length[3]');
$this->form_validation->set_rules('phone3', 'Phone 3', 'trim|required|numeric|max_length[4]');
// New End

$this->form_validation->set_rules('addr', 'Address', 'trim|required');
$this->form_validation->set_rules('state', 'State', 'trim|required');
$this->form_validation->set_rules('city', 'City', 'trim|required');
$this->form_validation->set_rules('zip', 'Zip', 'trim|required');
//	$this->form_validation->set_rules('stripetoken', 'stripetoken', 'trim|required');

$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[' . $this->db->dbprefix('users') . '.uemail]');
$this->form_validation->set_rules('password', 'Password', 'required');
$this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'required|matches[password]');

if ($this->form_validation->run()) {
    //$this->response = $_POST;
    //$id = isset($_POST['empid']) ? $_POST['empid'] : false;
    //$this->response += $this->user->createEmp($_POST['emp']);
    $this->response += $this->user->saveEmp($_POST);
    print_r($this->response);
    try {
        if (array_keys($this->response)[0] != 'ERR') {
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
				                        <p style="color: #000;font-size: 18px; text-align:cemter;">Hi, ' . $this->input->post('fname') . '!</p>
				                        <p style="color: #000;font-size: 14px;line-height: 22px">Please verify your email address to access well pass.</p>
				                      </td>
				                    </tr>
				                  </tbody>
				                </table>
				                <table border="0" cellpadding="0" cellspacing="0" width="100%">
				                  <tbody>
				                    <tr>
				                      <td align="center" style="padding: 20px">
				                        <a href="' . $this->user->verificationLink() . '" style="background-color: #a50101; color: #fff; display: inline-block;font-size: 16px;border-radius: 50px; text-decoration: none;text-transform: uppercase;padding: 20px 30px">Verify Now</a>
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
				                        <a href="' . $this->user->verificationLink() . '" style="background-color: #099e41; color: #fff; display: inline-block;font-size: 16px;border-radius: 50px; text-decoration: none;padding: 20px 30px">' . $this->user->verificationLink() . '</a>
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
            
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp.gmail.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'testtinngs@gmail.com';
            $config['smtp_pass'] = 'enzxkemhejwmvidr';
            $config['smtp_crypto'] = 'tls';
            
            $this->email->initialize($config);
            $this->email->to($_POST['email']);
            $this->email->from('web@wellpass.today', 'Well Ness');
            $this->email->subject('WellPass Account Verification');
            $this->email->message($htmlContent);
            if ($this->email->send()) {
                unset($this->response["REL"]);
                $this->response['RES_NO_RELOAD'] = 'Check your inbox or spam folder and verrify your email address to login';
            }else{
                $this->response['RES_NO_RELOAD'] = 'Email is not sent';
            }
        } else {
            $this->response += $response;
        }
    } catch (Exception $ex) {
        print_r($ex->getMessage());
    }
} else {
    $this->response['ERR'] = validation_errors();
}
