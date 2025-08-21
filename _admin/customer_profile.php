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
include('src/_top.php');

?>
<div class="wrapper" ng-controller="CustomerController">
    <!-- Preloader -->
    <?php include('src/_preloader.php'); ?>
    <!-- Navbar -->
    <?php include('src/_nav.php'); ?>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <?php include('src/_aside.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="row">
                    <div class="col-md-3">

                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center bg-success p-3">
                                    <img class="profile-user-img img-fluid img-circle bg-white"
                                        src="../assets/nit/img/user-avatar.png" alt="avatar">
                                </div>

                                <h3 class="profile-username text-center">Customer 1</h3>

                                <p class="text-muted text-center"><?php echo trans('text_since'); ?>:
                                    2025-07-01 
                                </p>

                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b> <?php echo trans('label_mobile_phone'); ?></b> <a
                                            class="float-right">+94769104866</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b> <?php echo trans('label_address'); ?></b> <a
                                            class="float-right">vavuniya</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b> <?php echo trans('label_email'); ?></b> <a
                                            class="float-right">customer@gmail.com</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b> <?php echo trans('label_due_limit'); ?></b> <a
                                            class="float-right">0</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b> <?php echo trans('label_payment_terms'); ?> <small>(Days)</small></b> <a
                                            class="float-right">0</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b><?php echo trans('text_total_invoice'); ?></b> <a class="float-right">
                                            0</a>
                                    </li>
                                </ul>

                                <a href="#" class="btn btn-primary btn-block" ng-click="customerEdit(<?php echo $customer['customer_id']; ?>, '<?php echo $customer['customer_name']; ?>')">
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
                                      
                                            <h4 class="info-box-text text-green">
                                                <?php echo trans('label_balance'); ?>
                                            </h4>
                                            
                                            <span id="balance" class="info-box-number">
                                                0.00
                                            </span>
                                     
                                        <hr style="margin-top:0;">
                                        <h4 class="info-box-text text-red">
                                            <?php echo trans('label_due'); ?>
                                        </h4>
                                        <span id="due" class="info-box-number">
                                            0.00
                                        </span>
                                        <hr style="margin-top:0;">
                                        
                                        <hr>
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
                                            <?php echo trans('text_invoice_list'); ?>
                                        </h3>
                                        <div class="card-tools">
                                            
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        $hide_colums = "";
                                        if (user_group_id() != 1) {
                                            if (!has_permission('access', 'read_sell_invoice')) {
                                                $hide_colums .= "8,";
                                            }
                                            if (!has_permission('access', 'sell_payment')) {
                                                $hide_colums .= "9,";
                                            }
                                        }
                                        ?>
                                        <div class="table-responsive">
                                            <!-- Invoice List Start-->
                                            <table id="invoice-invoice-list" class="table table-sm table-bordered table-striped"
                                                data-id=""
                                                data-hide-colums="<?php echo $hide_colums; ?>">
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
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <?php include('src/_control_sidebar.php'); ?>
    <!-- /.control-sidebar -->
    <!-- Main Footer -->
    <?php include('src/_footer.php'); ?>
</div>
<?php
include('src/_end.php');
?>
<script src="../assets/speed_pos_js_v1/angular/controller/CustomerProfileController.js"></script>