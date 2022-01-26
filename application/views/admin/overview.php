<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NRW</title>

<?php include 'css.php';?>
</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-closed control-sidebar-slide sidebar-collapse" data-panel-auto-height-mode="height">
<div class="wrapper">

   <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark" id="includeRightbar">
    <?php include 'rightbar.php';?>
    </nav>
    <!-- /.navbar -->


  <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Main Sidebar Container -->
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2" >
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" id="includeSidebar">
          <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
           <?php include 'sidebar.php';?>
         </ul>
       </nav>
       <!-- /.sidebar-menu -->
     </div>
   </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper iframe-mode" data-widget="iframe" data-loading-screen="750">
    <div class="nav navbar navbar-expand navbar-white navbar-light border-bottom p-0">
      <div class="nav-item dropdown">
        <a class="nav-link bg-danger dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Close</a>
        <div class="dropdown-menu mt-0">
          <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all">Close All</a>
          <a class="dropdown-item" href="#" data-widget="iframe-close" data-type="all-other">Close All Other</a>
        </div>
      </div>
      <a class="nav-link bg-light" href="#" data-widget="iframe-scrollleft"><i class="fas fa-angle-double-left"></i></a>
      <ul class="navbar-nav overflow-hidden" role="tablist"></ul>
      <a class="nav-link bg-light" href="#" data-widget="iframe-scrollright"><i class="fas fa-angle-double-right"></i></a>
      <a class="nav-link bg-light" href="#" data-widget="iframe-fullscreen"><i class="fas fa-expand"></i></a>
    </div>
    <div class="tab-content">
      <div class="tab-content">
        <!-- <object style="" type="text/html" data="flow/1" ></object> -->
        <!-- <img src="<?php echo base_url('assets/gambar/mendoan.jpg'); ?>" style="width: 90%; height: 60%; "  /> -->
      </div>
      <div class="tab-loading">
        <div>
          <h2 class="display-4">Tab is loading <i class="fa fa-sync fa-spin"></i></h2>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Footer -->
    <footer class="main-footer" id="includeFooter">
      <?php include 'footer.php';?>
    </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include 'js.php';?>


</body>
</html>
