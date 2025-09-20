<?php
// pos v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_missing_stock(s)'));
$document->setController('MissingStockController');
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
    <h5><?php echo trans(current_nav() == 'missing_reports' ? "label_missing_stock(s)_report" : "label_missing_stock(s)_list") ?></h5>
  </div>
  <div class="card-tool"></div>
  <div class="card-body">
    <?php
    $hide_colums = "";
    if (user_group_id() != 1) {
      if (!has_permission('access', 'read_order_invoice')) {
        $hide_colums .= "8,";
      }
      if (!has_permission('access', 'order_payment')) {
        $hide_colums .= "9,";
      }
    }
    ?>
    <div class="table-responsive">
      <table id="missing-stocks-list" class="table table-sm table-bordered table-striped">
        <thead>
          <tr class="bg-primary">
            <th><?php echo trans("label_No.") ?></th>
            <th><?php echo trans("label_date") ?></th>
            <th><?php echo trans("label_barcode") ?></th>
            <th><?php echo trans("label_jewelary") ?></th>
            <th><?php echo trans("label_status") ?></th>
            <th><?php echo trans("label_checked_history") ?></th>
          </tr>
        </thead>
        <tfoot>
          <tr class="bg-primary">
            <th><?php echo trans("label_No.") ?></th>
            <th><?php echo trans("label_date") ?></th>
            <th><?php echo trans("label_barcode") ?></th>
            <th><?php echo trans("label_jewelary") ?></th>
            <th><?php echo trans("label_status") ?></th>
            <th><?php echo trans("label_checked_history") ?></th>
          </tr>
        </tfoot>
    </div>
  </div>
</div>
</div>
<?php
include('src/_end.php');
?>