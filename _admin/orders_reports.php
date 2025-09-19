<?php
// pos v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
  redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_orders_report'));
$document->setController('OrdersReportController');
include('src/_top.php');
?>



<!-- Control Sidebar -->
<?php include('src/_control_sidebar.php'); ?>
<!-- /.control-sidebar -->
<!-- Main Footer -->
<?php //include('src/_footer.php'); 
?>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">
      <?php echo trans('text_order_report'); ?>
    </h3>
    <div class="card-tools">

    </div>
  </div>
  <div class="card-body">
    <?php
    $hide_colums = "12, 13,";
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
      <table id="order-report" class="table table-sm table-bordered table-striped" data-hide-colums="<?php echo $hide_colums; ?>">
        <thead class="bg-primary">
          <tr>
            <th><?php echo trans("label_#") ?></th>
            <th><?php echo trans("label_order_no") ?></th>
            <th><?php echo trans("label_date") ?></th>
            <th><?php echo trans("label_customer") ?></th>
            <th><?php echo trans("label_order") ?></th>
            <th><?php echo trans("label_total_payable") ?></th>
            <th><?php echo trans("label_advance") ?></th>
            <th><?php echo trans("label_total_paid") ?></th>
            <th><?php echo trans("label_outsatnding") ?></th>
            <th><?php echo trans("label_biller") ?></th>
            <th><?php echo trans("label_pay") ?></th>
            <th><?php echo trans("label_view") ?></th>
            <th><?php echo trans("label_edit") ?></th>
            <th><?php echo trans("label_delete") ?></th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot class="bg-primary">
          <tr>
            <th><?php echo trans("label_#") ?></th>
            <th><?php echo trans("label_order_no") ?></th>
            <th><?php echo trans("label_date") ?></th>
            <th><?php echo trans("label_customer") ?></th>
            <th><?php echo trans("label_order") ?></th>
            <th><?php echo trans("label_total_payable") ?></th>
            <th><?php echo trans("label_advance") ?></th>
            <th><?php echo trans("label_total_paid") ?></th>
            <th><?php echo trans("label_outsatnding") ?></th>
            <th><?php echo trans("label_biller") ?></th>
            <th><?php echo trans("label_pay") ?></th>
            <th><?php echo trans("label_view") ?></th>
            <th><?php echo trans("label_edit") ?></th>
            <th><?php echo trans("label_delete") ?></th>
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