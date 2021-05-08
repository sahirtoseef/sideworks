<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?= isset($title) ? "Well Pass Admin - $title" :  "Well Pass Admin"; ?></title>
  <script>const gapi = '<?= base_url('api'); ?>';</script>
  <!-- Custom fonts for this template-->
  <link href="<?= assets("admin/vendor/fontawesome-free/css/all.min.css"); ?>" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= assets("admin/css/sb-admin-2.min.css"); ?>" rel="stylesheet">

  <link href="<?= assets("admin/vendor/datatables/dataTables.bootstrap4.min.css"); ?>" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="./">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-smile"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Well Pass</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
     
      <?php if($user->isSuperAdmin()){ ?>
		   <li class="nav-item active">
        <a class="nav-link" href="./">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <!-- Divider -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/forms'); ?>">
          <i class="fas fa-fw fa-edit"></i>
          <span>Forms</span></a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/employers'); ?>">
          <i class="fas fa-fw fa-user-circle"></i>
          <span>Employers</span></a>
      </li>
      <?php } ?>
      
      <?php       
		$user_data = '';
		$user_result = $this->data;
		if(count($user_result) > 0) {
			$info = isset($user_result['user']->info->subscription_status) ? $user_result['user']->info->subscription_status : '';
                        if($info == '') {
				$user_data = 0;
			} else {
				$user_data = 1;
			}
                        if( $user_result['user']->info->urole == 1 ){
                            $user_data = 1;
                        }
		} else {
			$user_data = 0;
		}
	  ?>
	  <?php if($user_data == 1) { ?>
                <?php if(!$user->isSuperAdmin()){ ?>
			 <li class="nav-item active">
				<a class="nav-link" href="./">
				  <i class="fas fa-fw fa-tachometer-alt"></i>
				  <span>Dashboard</span></a>
			  </li>
                <?php } ?>
			<li class="nav-item">
			<a class="nav-link" href="<?= base_url('admin/submissions'); ?>" >
			  <i class="fas fa-fw fa-id-card"></i>
			  <span>Submissions</span></a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href="<?= base_url('admin/employees'); ?>">
			  <i class="fas fa-fw fa-users"></i>
			  <span>Employees</span></a>
			</li>
	  <?php } else { ?>
		 <li class="nav-item active">
			<a class="nav-link" >
			  <i class="fas fa-fw fa-tachometer-alt"></i>
			  <span>Dashboard</span></a>
		  </li>
			<li class="nav-item" class="restricted_access">
			<a class="nav-link"  >
			  <i class="fas fa-fw fa-id-card"></i>
			  <span>Submissions</span></a>
			</li>
			<li class="nav-item" class="restricted_access">
			<a class="nav-link" >
			  <i class="fas fa-fw fa-users"></i>
			  <span>Employees</span></a>
			</li>
		  
	  <?php } ?>	
      
		<li class="nav-item">
			<a class="nav-link" href="<?= base_url('admin/billing'); ?>">
			  <i class="fas fa-fw fa-users"></i>
			  <span>Billing</span></a>
			</li>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading --
      <div class="sidebar-heading">
        Addons
      </div>

      <!-- Nav Item - Pages Collapse Menu 
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="fas fa-fw fa-folder"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Login Screens:</h6>
            <a class="collapse-item" href="<?= base_url('admin/login'); ?>">Login</a>
            <a class="collapse-item" href="<?= base_url('admin/register'); ?>">Register</a>
            <a class="collapse-item" href="<?= base_url('admin/forgotpassword'); ?>">Forgot Password</a>
            <div class="collapse-divider"></div>
            <h6 class="collapse-header">Other Pages:</h6>
            <a class="collapse-item" href="<?= base_url('admin/404'); ?>">404 Page</a>
            <a class="collapse-item" href="<?= base_url('admin/blank'); ?>">Blank Page</a>
          </div>
        </div>
      </li>
-->
      

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-dark topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-white">
            <i class="fa fa-bars"></i>
          </button>

          

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link text-white dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              
            </li>

            <!-- Nav Item - Notifications --
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link  text-white dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Messages --
                <span class="badge badge-danger badge-counter">7</span>
              </a>
              <!-- Dropdown - Messages -
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header bg-gray-900">
                  Notifications
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">User 1 has submitted the form.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                    <div class="status-indicator"></div>
                  </div>
                  <div>
                    <div class="text-truncate">User created an account</div>
                    <div class="small text-gray-500">Jae Chun · 1h</div>
                  </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                    <div class="status-indicator bg-warning"></div>
                  </div>
                  <div>
                    <div class="text-truncate">User is filling the form</div>
                    <div class="small text-gray-500">Morgan Alvarez · 1m</div>
                  </div>
                </a>
                
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Notifications</a>
              </div>
              
            </li> -->

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle  text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white-600 small"><?= $user->info->fname.' '.$user->info->lname; ?></span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= base_url('profile'); ?>">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <!--
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a> -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->
        
<!-- Begin Page Content -->
<div class="container-fluid">
