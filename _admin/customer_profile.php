<?php
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
if (user_group_id() != 1 && !has_permission('access', 'read_customer')) {
    redirect(root_url() . '/' . ADMINDIRNAME . '/dashboard.php');
}
$document->setTitle(trans('title_customers'));
$document->setController('CustomerProfileController');

$due_amount = 0;
$outstanding = 0;
$next_due_date = null;
$isActiveorder = false;

if (isset($request->get['customer']) && $request->get['customer'] != null) {
    $customer_model = registry()->get('loader')->model('customer');
    $order_model = registry()->get('loader')->model('order');
    $payment_model = registry()->get('loader')->model('payment');

    $lid = null;
    if (isset($request->get['order']) && $request->get['order'] != null) {
        $lid = $request->get['order'];
    }
    $cid = $_GET['customer'];
    $the_customer = $customer_model->getcustomer($cid);
    $order = $order_model->getCustomerOrders($cid);
    if ($order) {
        $the_order = $order[0];
        if ($the_order) {
            $total_amt = $the_order['total_amt'];
            $total_paid = $the_order['total_paid'];
            $payment = $payment_model->get_order_last_payment($the_order['id']);
            $due_count = 0;
            $the_payment = $payment;
            if ($the_payment) {
                $last_payment_date = new DateTime(date('Y-m-d', strtotime($the_payment['created_at'])));
                $total_paid = $the_order['total_paid'];
                $total_amt = $the_order['total_amt'];


                $today = new DateTime(date("Y-m-d"));
                $days_diff = $last_payment_date->diff($today);

                // $due_count = floor($days_diff->days / $type_days_count);

                $next_due_date = $last_payment_date;
                // if ($payment_type == 'monthly') {
                //     $next_due_date->modify('+' . ($due_count + 1) . ' month');
                // } else {
                //     $next_due_date->modify('+' . ($due_count + 1) . ' week');
                // }

                $next_due_date = $next_due_date->format('d M Y');


                // Due amount
                // $due_amount = $due_count * $installment;

                $balance = $total_amt - $total_paid;
                if ($due_amount > $balance) {
                    $due_amount = $balance;
                }
            }

            $next_due_date = new DateTime(date('Y-m-d', strtotime($the_order['created_at'])));
            // if ($payment_type == 'monthly') {
            //     $next_due_date->modify('+' . ($due_count + 1) . ' month');
            // } else {
            //     $next_due_date->modify('+' . ($due_count + 1) . ' week');
            // }

            $next_due_date = $next_due_date->format('d M Y');

            // Outstanding
            $outstanding = $total_amt - $total_paid;
        }
        $isActiveorder = true;
    }
} else {
    redirect(root_url() . '/' . ADMINDIRNAME . '/customer.php');
}
include('src/_top.php');

?>

<!-- Content Wrapper. Contains page content -->
<div class="row" ng-controller="CustomerProfileController">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center bg-success p-3">
                    <img class="profile-user-img img-fluid img-circle bg-white" src="../assets/nit/img/user-avatar.png"
                        alt="avatar">
                </div>

                <h3 class="profile-username text-center"><?php echo $the_customer['c_name'] ?></h3>

                <p class="text-muted text-center"><?php echo trans('text_since'); ?>:
                    <?php echo $the_customer['created_at'] ?>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b> <?php echo trans('label_mobile_phone'); ?></b> <a class="float-right"><?php echo $the_customer['c_mobile'] ?></a>
                    </li>
                    <li class="list-group-item">
                        <b> <?php echo trans('label_address'); ?></b> <a class="float-right"><?php echo $the_customer['c_address'] ?></a>
                    </li>

                    <!-- <li class="list-group-item">
                        <b> <?php // echo trans('label_due_limit'); 
                            ?></b> <a class="float-right"><?= "" // $p_count  
                                                                                                    ?></a>
                    </li> -->

                    <li class="list-group-item">
                        <b><?php echo trans('text_total_invoice'); ?></b> <a class="float-right">
                            0</a>
                    </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block">
                    <b><i class="fa fa-fw fa-edit"></i>
                        Edit</b></a>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-4 bg-success">
                        <h1> <i>Rs</i></h1>
                    </div>
                    <div class="col-8">

                        <!-- <h4 class="info-box-text text-green">
                            <?php //echo trans('label_balance'); 
                            ?>
                        </h4>

                        <span id="balance" class="info-box-number">
                            0.00
                        </span> -->

                        <hr style="margin-top:0;">
                        <h4 class="info-box-text text-red">
                            <?php echo trans('label_due'); ?>
                        </h4>
                        <span id="due" class="info-box-number">
                            <?php echo $the_customer['total_due'] ?>
                        </span>
                        <hr style="margin-top:0;">

                        <!-- <hr> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?php echo trans('text_order_list'); ?>
                        </h3>
                        <div class="card-tools">

                        </div>
                    </div>
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
                            <!-- Invoice List Start-->
                            <table id="customer-order-list" class="table table-sm table-bordered table-striped"
                                data-id="" data-hide-colums="<?php echo $hide_colums; ?>">
                                <thead class="bg-primary">
                                    <tr>
                                        <th><?php echo trans('label_date'); ?></th>
                                        <th><?php echo trans('label_invoice_id'); ?></th>
                                        <th><?php echo trans('label_note'); ?></th>
                                        <th><?php echo trans('label_items'); ?></th>
                                        <th><?php echo trans('label_invoice_amount'); ?></th>
                                        <th><?php echo trans('label_prev_due'); ?></th>
                                        <th><?php echo trans('label_payable'); ?></th>
                                        <th><?php echo trans('label_paid'); ?></th>
                                        <th><?php echo trans('label_due'); ?></th>
                                        <th><?php echo trans('label_view'); ?></th>
                                        <th><?php echo trans('label_pay'); ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="bg-primary">
                                        <th><?php echo trans('label_date'); ?></th>
                                        <th><?php echo trans('label_invoice_id'); ?></th>
                                        <th><?php echo trans('label_note'); ?></th>
                                        <th><?php echo trans('label_items'); ?></th>
                                        <th><?php echo trans('label_invoice_amount'); ?></th>
                                        <th><?php echo trans('label_prev_due'); ?></th>
                                        <th><?php echo trans('label_payable'); ?></th>
                                        <th><?php echo trans('label_paid'); ?></th>
                                        <th><?php echo trans('label_due'); ?></th>
                                        <th><?php echo trans('label_view'); ?></th>
                                        <th><?php echo trans('label_pay'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?php echo trans('text_payment_list'); ?>
                        </h3>
                        <div class="card-tools">

                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $hide_colums = "";
                        if (user_group_id() != 1) {
                            if (!has_permission('access', 'read_payment_invoice')) {
                                $hide_colums .= "8,";
                            }
                            if (!has_permission('access', 'payment_payment')) {
                                $hide_colums .= "9,";
                            }
                        }
                        ?>
                        <div class="table-responsive">
                            <!-- Invoice List Start-->
                            <table id="customer-payment-list" class="table table-sm table-bordered table-striped"
                                data-id="" data-hide-colums="<?php echo $hide_colums; ?>">
                                <thead class="bg-primary">
                                    <tr>
                                        <th><?php echo trans('label_date'); ?></th>
                                        <th><?php echo trans('label_invoice_id'); ?></th>
                                        <th><?php echo trans('label_note'); ?></th>
                                        <th><?php echo trans('label_items'); ?></th>
                                        <th><?php echo trans('label_invoice_amount'); ?></th>
                                        <th><?php echo trans('label_prev_due'); ?></th>
                                        <th><?php echo trans('label_payable'); ?></th>
                                        <th><?php echo trans('label_paid'); ?></th>
                                        <th><?php echo trans('label_due'); ?></th>
                                        <th><?php echo trans('label_view'); ?></th>
                                        <th><?php echo trans('label_pay'); ?></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="bg-primary">
                                        <th><?php echo trans('label_date'); ?></th>
                                        <th><?php echo trans('label_invoice_id'); ?></th>
                                        <th><?php echo trans('label_note'); ?></th>
                                        <th><?php echo trans('label_items'); ?></th>
                                        <th><?php echo trans('label_invoice_amount'); ?></th>
                                        <th><?php echo trans('label_prev_due'); ?></th>
                                        <th><?php echo trans('label_payable'); ?></th>
                                        <th><?php echo trans('label_paid'); ?></th>
                                        <th><?php echo trans('label_due'); ?></th>
                                        <th><?php echo trans('label_view'); ?></th>
                                        <th><?php echo trans('label_pay'); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

<?php
include('src/_end.php');
?>