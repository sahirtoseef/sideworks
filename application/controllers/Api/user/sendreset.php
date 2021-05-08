<?php

$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

if ($this->form_validation->run()){
  if(!$this->user->verificationLink($_POST['email'])){
    $this->response['ERR'] = 'USER_NOT_FOUND';
  }else{
    $id = $this->user->getID($_POST['email']);
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
                        <p style="color: #000;font-size: 18px; text-align:cemter;">Hi, '.$this->user->getInfo('fname', $id).'!</p>
                        <p style="color: #000;font-size: 14px;line-height: 22px">Please open the below link to change your password.</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tbody>
                    <tr>
                      <td align="center" style="padding: 20px">
                        <a href="'.$this->user->resetlink($_POST['email']).'" style="background-color: #a50101; color: #fff; display: inline-block;font-size: 16px;border-radius: 50px; text-decoration: none;text-transform: uppercase;padding: 20px 30px">Change Password</a>
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
                        <a href="'.$this->user->resetlink($_POST['email']).'" style="background-color: #099e41; color: #fff; display: inline-block;font-size: 16px;border-radius: 50px; text-decoration: none;padding: 20px 30px">'.$this->user->resetlink($_POST['email']).'</a>
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
    $this->email->from('web@wellpass.today','WellPass');
    $this->email->subject('WellPass Password Change');
    $this->email->message($htmlContent);
    if($this->email->send()){
    	$this->response['RES'] = 'EMAIL_SENT';
    }
  }
}else{
  $this->response['ERR'] = validation_errors();
}