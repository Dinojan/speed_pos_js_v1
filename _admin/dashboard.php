<?php
// dashboard v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_dashboard'));
$document->setController('DashboardController');
include('src/_top.php');
?>

<div class="wrapper">
    <!-- Preloader -->
    <?php include('src/_preloader.php'); ?>
    <!-- Navbar -->
    <?php include('src/_nav.php'); ?>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <?php include('src/_aside.php'); ?>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 card p-2 d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold" 
                        style="font-size: 1.3rem; color: rgb(140, 140, 140); font-weight: 500;">
                        Welcome to <?php echo store('name'); ?>
                    </span>
                    <img src="../assets/nit/img/<?= (store('logo')) ? store('logo') : 'nit.png'; ?>" alt="logo"
                        width="100px" style="opacity: .8;">
                </div>
            </div>
        </div>

  
    <!-- Control Sidebar -->
    <?php include('src/_control_sidebar.php'); ?>
    <!-- /.control-sidebar -->
    <!-- Main Footer -->
    <?php include('src/_footer.php'); ?>
</div>
<?php
include('src/_end.php');
?>
