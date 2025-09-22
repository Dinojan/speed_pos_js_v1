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

$total_products = get_count('product', null, null, 0);
$gold_products = get_count('product', 'material', 2, 0);
$silver_products = get_count('product', 'material', 8, 0);
$customers = get_count('customer');
$orders = get_count('orders');
$unfinished_orders = get_count('orders', 'order_status', 0);
$invoices = get_count('invoice_info', null, null, 0);
$sold = get_count('product', null, null, 3);
$sold = get_count('product', null, null, 3);
$today_sold = get_count('invoice_info', 'created_at', null, null, true);
$today_pre_order = get_count('orders', 'order_status', 0, null, true);

$total_golds_weight = get_sum('product', 'wgt', 'material', 2, 0);
$total_silver_weight = get_sum('product', 'wgt', 'material', 8, 0);
$unfinished_orders_amount = get_sum('orders', 'total_amt', 'order_status', 0);
$unfinished_orders_total_paid = get_sum('orders', 'total_paid', 'order_status', 0);
$unfinished_orders_outstanding = $unfinished_orders_amount - $unfinished_orders_total_paid;
$invoice_outstanding = get_sum('invoice_info', 'outstanding', null, null, 0);
$today_sales_amount = get_sum('invoice_info', 'total_payable', null, null, 0, true);
$today_orders_amount = get_sum('orders', 'total_amt', 'order_status', 0, null, true);
?>

<div class="wrapper">
    <div class="content-header">
        <div class="container-fluid p-0">
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

    <div class="card-body row p-1">
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_total_pieces") ?></span><span class="badge bg-warning"><?php echo $total_products ?></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_gold_pieces") ?></span><span><span class="badge bg-warning"><?php echo $gold_products ?></span> <span class="badge bg-warning"><?php echo $total_golds_weight . 'g' ?></span></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_silver_pieces") ?></span><span><span class="badge bg-warning"><?php echo $silver_products ?></span> <span class="badge bg-warning"><?php echo $total_silver_weight . 'g' ?></span></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_customers") ?></span><span class="badge bg-warning"><?php echo $customers ?></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_total_pre_orders") ?></span><span class="badge bg-warning"><?php echo $orders ?></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_unfinished_pre_orders") ?></span><span><span class="badge bg-warning"><?php echo $unfinished_orders ?></span> <span class="badge bg-warning"><?php echo 'Rs.' . number_format($unfinished_orders_outstanding, 2) ?></span></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_total_invoices") ?></span><span class="badge bg-warning"><?php echo $invoices ?></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_invoice_outstandings") ?></span><span><span class="badge bg-warning"><?php echo $invoices ?></span> <span class="badge bg-warning"><?php echo 'Rs.' . number_format($invoice_outstanding, 2) ?></span></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_sold_pieces") ?></span><span class="badge bg-warning"><?php echo $sold ?></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_today's_sale") ?></span><span><span class="badge bg-warning"><?php echo $today_sold ?></span> <span class="badge bg-warning"><?php echo 'Rs.' . number_format($today_sales_amount, 2) ?></span></span></p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-xl-2 px-1 pb-2">
            <div class="col-12 border border-primary rounded py-2 px-3">
                <p class="font-weight-bold m-0 row justify-content-between"><span><?php echo trans("text_today's_pre_orders") ?></span><span><span class="badge bg-warning"><?php echo $today_sold ?></span> <span class="badge bg-warning"><?php echo 'Rs.' . number_format($today_orders_amount, 2) ?></span></p>
            </div>
        </div>
    </div>

    <form ng-submit="updateMaterialPrice()" id="material-price-update" class="card card-outline card-primary mt-2">
        <div class="card-header py-2 px-3">
            <h6><?php echo trans("label_today_materials_price") . ' (' . date("d F, Y") . ')' ?></h6>
        </div>
        <div class="row p-4">
            <div class="form-group col-12 col-sm-6 col-xl-3 py-2 pr-2 m-0">
                <label>22k Gold <span class="text-danger">*</span></label>
                <input type="text" ng-model="materials.gold22" class="form-control" placeholder="22k Gold Price">
            </div>
            <div class="form-group col-12 col-sm-6 col-xl-3 py-2 px-2 m-0">
                <label>24k Gold <span class="text-danger">*</span></label>
                <input type="text" ng-model="materials.gold24" class="form-control" placeholder="24k Gold Price">
            </div>
            <div class="form-group col-12 col-sm-6 col-xl-3 py-2 px-2 m-0">
                <label>Silver <span class="text-danger">*</span></label>
                <input type="text" ng-model="materials.silver" class="form-control" placeholder="Silver Price">
            </div>
            <div class="form-group col-12 col-sm-6 col-xl-3 pl-2 m-0 pt-2 mt-1">
                <button type="submit" class="btn btn-success w-100 mt-4">
                    <i class="fas fa-save"></i> Update Price
                </button>
            </div>
        </div>
    </form>

</div>
<?php
include('src/_end.php');
?>