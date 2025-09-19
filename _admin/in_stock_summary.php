<?php
// pos v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_in_stock_summary'));
$document->setController('CheckStockController');
include('src/_top.php');
?>

<!-- Control Sidebar -->
<?php include('src/_control_sidebar.php'); ?>
<!-- /.control-sidebar -->
<!-- Main Footer -->
<?php // include('src/_footer.php'); 
?>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h5 class="text-black"><?php echo trans("label_checked_jewelries") ?></h5>
  </div>
  <div class="card-tools"></div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="checked-jewels-list" class="table table-sm table-bordered table-striped">
        <thead>
          <tr class="bg-primary">
            <th><?php echo trans("label_No.") ?></th>
            <th><?php echo trans("label_date") ?></th>
            <th><?php echo trans("label_barcode") ?></th>
            <th><?php echo trans("label_jewelary") ?></th>
            <th><?php echo trans("label_status") ?></th>
            <th><?php echo trans("label_checked_count") ?></th>
            <th><?php echo trans("label_checker") ?></th>
          </tr>
        </thead>
        <tfoot>
          <tr class="bg-primary">
            <th><?php echo trans("label_No.") ?></th>
            <th><?php echo trans("label_date") ?></th>
            <th><?php echo trans("label_barcode") ?></th>
            <th><?php echo trans("label_jewelary") ?></th>
            <th><?php echo trans("label_status") ?></th>
            <th><?php echo trans("label_checked_count") ?></th>
            <th><?php echo trans("label_checker") ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
</div>
<?php
include('src/_end.php');
?>