<?php
// pos v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_sales_return'));
$document->setController('SalesReturnController');
include('src/_top.php');
?>

  <!-- Control Sidebar -->
    <?php include('src/_control_sidebar.php'); ?>
    <!-- /.control-sidebar -->
    <!-- Main Footer -->
    <?php // include('src/_footer.php'); ?>
</div>
<?php
include('src/_end.php');
?>