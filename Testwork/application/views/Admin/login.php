<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Well Pass Admin - Login</title>
  <script>const gapi = '<?= base_url('api'); ?>';</script>
  <!-- Custom fonts for this template-->
  <link href="<?= assets('admin/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= assets('admin/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

</head>

<body class="bg-gradient-danger">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center vh-100">

      <div class="col-xl-6 col-lg-6 col-md-9 align-self-center">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!---<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>---->
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                  <a href="http://www.air.sideworkapps.com/"><img src="<?php echo base_url();?>assets/default/images/high-resolution-logo.png" class="img-fluid" style="max-width: 100%;"></a>
                    <h1 class="h4 text-gray-900 mb-4">Well Pass Admin</h1>
                  </div>
                  <form id="userLogin" class="user postform" method="POST" redirect="<?= base_url('admin'); ?>">
                    <div class="form-group">
                      <input type="email" class="form-control form-control-user" id="user" name="user" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="password" name="pwd" placeholder="Password">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember" />
                        <label class="custom-control-label" for="remember">Remember Me</label>
                      </div>
                    </div>
                    <div class="response"></div>
                    <input type="hidden" name="role" value="1">
                    <input type="hidden" id="token" name="<?= csrf('name'); ?>" value="<?= csrf(); ?>" />
                    <input type="hidden" id="action" name="action" value="user::login" />
                    <button type="submit" class="btn btn-success btn-user btn-block">
                      Login
                    </button>
                    
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="<?= base_url('admin/forgotpassword'); ?>">Forgot Password?</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= assets('admin/vendor/jquery/jquery.min.js'); ?>"></script>
  <script src="<?= assets('admin/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= assets('admin/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= assets('admin/js/sb-admin-2.js'); ?>"></script>

</body>

</html>