<?php
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
if (user_group_id() != 1 && !has_permission('access', 'read_supplier')) {
    redirect(root_url() . '/' . ADMINDIRNAME . '/dashboard.php');
}
$document->setTitle(trans('title_suppliers'));
$document->setController('SupplierProfileController');
if (isset($request->get['supplier']) && $request->get['supplier'] != null) {
    $the_supplier = get_the_supplier($request->get['supplier']);
    if (!$the_supplier) {
        redirect(root_url() . '/' . ADMINDIRNAME . '/supplier.php');
    }
    $p_count = 0;
    $statement = db()->prepare("SELECT * FROM `product` WHERE `s_id` = ?");
    $statement->execute([$request->get['supplier']]);
    $p_count = $statement->rowCount();
    $due_to_payment = get_sum('product', 'cost', 's_id', $request->get['supplier']) -  $the_supplier['total_paid'];
} else {
    redirect(root_url() . '/' . ADMINDIRNAME . '/supplier.php');
}
include('src/_top.php');

?>

<!-- Content Wrapper. Contains page content -->
<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center bg-success p-3">
                    <img class="profile-user-img img-fluid img-circle bg-white" src="../assets/nit/img/user-avatar.png"
                        alt="avatar">
                </div>

                <h3 class="profile-username text-center"><?php echo $the_supplier['s_name'] ?></h3>

                <p class="text-muted text-center"><?php echo trans('text_since'); ?>:
                    <?php echo date('d F, Y', strtotime($the_supplier['created_at'])); ?>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b> <?php echo trans('label_mobile_phone'); ?></b> <a
                            class="float-right"><?php echo format_mobile($the_supplier['s_mobile']) ?></a>
                    </li>

                    <li class="list-group-item">
                        <b> <?php echo trans('due_to_pay'); ?></b> <a
                            class="float-right"><?php echo 'Rs. ' . number_format($due_to_payment, 2) ?></a>
                    </li>

                    <li class="list-group-item">
                        <b><?php echo trans('total_products'); ?></b> <a class="float-right">
                            <?= $p_count ?></a>
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
                                            </h4> -->

                        <!-- <span id="balance" class="info-box-number">
                                                0.00
                                            </span> -->

                        <hr style="margin-top:0;">
                        <h4 class="info-box-text text-red">
                            <?php echo trans('label_due'); ?>
                        </h4>
                        <span id="due" class="info-box-number">
                            <?php echo 'Rs. ' . number_format($due_to_payment, 2) ?>
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
                            <?php echo trans('text_jewellary_list'); ?>
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
                            <table id="supplier-jewels-list" class="table table-sm table-bordered table-striped"
                                data-id="" data-hide-colums="<?php echo $hide_colums; ?>">
                                <thead class="bg-blue">
                                    <tr>
                                        <th class="w-5">#</th>
                                        <th class="w-10"><?= trans("label_product_code"); ?></th>
                                        <th class="w-20"><?= trans("label_name"); ?></th>
                                        <th class="w-15"><?= trans("label_category"); ?></th>
                                        <th class="w-20"><?= trans("label_supplier"); ?></th>
                                        <th class="w-5"><?= trans("label_weight"); ?></th>
                                        <th class="w-5"><?= trans("label_cost"); ?></th>
                                        <th class="w-5"><?= trans("label_status"); ?></th>
                                        <th class="w-5"><?= trans("label_view"); ?></th>
                                        <th class="w-5"><?= trans("label_edit"); ?></th>
                                        <th class="w-5">
                                            <?= (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2)
                                                ? trans("label_restore")
                                                : trans("label_delete"); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot class="bg-blue">
                                    <tr>
                                        <th class="w-5">#</th>
                                        <th class="w-10"><?= trans("label_product_code"); ?></th>
                                        <th class="w-20"><?= trans("label_name"); ?></th>
                                        <th class="w-15"><?= trans("label_category"); ?></th>
                                        <th class="w-20"><?= trans("label_supplier"); ?></th>
                                        <th class="w-5"><?= trans("label_weight"); ?></th>
                                        <th class="w-5"><?= trans("label_cost"); ?></th>
                                        <th class="w-5"><?= trans("label_status"); ?></th>
                                        <th class="w-5"><?= trans("label_view"); ?></th>
                                        <th class="w-5"><?= trans("label_edit"); ?></th>
                                        <th class="w-5">
                                            <?= (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2)
                                                ? trans("label_restore")
                                                : trans("label_delete"); ?>
                                        </th>
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