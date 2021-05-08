<?php

$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
$this->form_validation->set_rules('name', 'Name', 'trim|required');
$this->form_validation->set_rules('message', 'Message', 'trim|required');

if ($this->form_validation->run()){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $message = $_POST['message'];

    // SEND confirmation email to the requested email
    $htmlContentConfirmationEmail = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
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
                              <p style="color: #000;font-size: 18px; text-align:cemter;">Hi, '.$name.'!</p>
                              <p style="color: #000;font-size: 14px;line-height: 22px">This is an automated response to let you know that your request has been received and is being reviewed by our Member Services Team.<br>
                              To add additional comments, reply to this email.</p>
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
    $this->email->to($email);
    $this->email->from('web@wellpass.today','WellPass');
    $this->email->subject('WellPass Contact Form Query Acknowledgement');
    $this->email->message($htmlContentConfirmationEmail);
    
    if($this->email->send()){
      // Send the query email to
      $htmlContentQueryEmail = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
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
                              <p style="color: #000;font-size: 18px; text-align:cemter;">New Contact Us form query received!</p>
                              <p style="color: #000;font-size: 14px;line-height: 22px">
                              Name: <b>'. $name .'</b>
                              <br>
                              Email: <b>'. $email .'
                              <br>
                              Message: <b>'. $message .'
                              </p>
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
    	$this->email->initialize($config);
      $this->email->to("ecpropertyinv@gmail.com");
      $this->email->from('web@wellpass.today','WellPass');
      $this->email->subject('WellPass|New Contact Form Query');
      $this->email->message($htmlContentQueryEmail);
      $this->response['RES'] = 'EMAIL_SENT';
    }
}else{
  $this->response['ERR'] = validation_errors();
}