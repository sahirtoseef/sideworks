<?php

if ($this->user->isAdmin()) {
    $this->form_validation->set_rules('stripe_token', 'Stripe token', 'trim|required');
    if ($this->form_validation->run()) {
        //$this->response = $_POST;
        $stripe_token = isset($_POST['stripe_token']) ? $_POST['stripe_token'] : false;
        $this->response += $this->user->update_plan_data($stripe_token);
        $employer = $this->user->getEmployer();
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
                        <p style="color: #000;font-size: 18px; text-align:cemter;">Hi, ' . $employer->empname . '!</p>
                        <p style="color: #000;font-size: 14px;line-height: 22px">Thank you for subscription. You have been charged for $10 for a month.</p>
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
        $this->email->to($employer->email);
        $this->email->from('web@wellpass.today', 'WellPass');
        $this->email->subject('WellPass Subscription');
        $this->email->message($htmlContent);
        if ($this->email->send()) {
            $this->response['RES'] = 'EMAIL_SENT';
        }else{
            $this->response['ERR'] = 'Email notification is not sent...!';
        }
    } else {
        $this->response['ERR'] = validation_errors();
    }
} else {
    $this->response['ERR'] = 'Non Admin Access not allowed!';
}
