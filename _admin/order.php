<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Order');
$document->setController('OrderController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h3 class="card-title"><?= trans('title_orders') ?>
                    <?php if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
                        echo "( <i class ='fas fa-trash text-danger'> Bin</i>  )";
                    } ?>
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" ng-click="openAddorderModal()">
                        <i class="fas fa-plus"></i> <?= trans('button_add_order') ?>
                    </button>
                    <?php if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
                    ?>
                        <a href="order.php" type="button" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-list"></i> </a>

                    <?php
                    } else {
                    ?>
                        <a href="?isdeleted=2" type="button" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </a>
                    <?php
                    } ?>

                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    $hide_colums = "";
                    if (user_group_id() != 1) {
                        if (!has_permission('access', 'update_order')) {
                            $hide_colums .= "9,";
                        }
                        if (!has_permission('access', 'delete_order')) {
                            $hide_colums .= "10,";
                        }
                    }
                    if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
                        $hide_colums .= "9,";
                    }

                    ?>
                    <div class="card-body p-0">
                        <table id="orderTable" data-hide-colums="<?php echo $hide_colums; ?>" class="table table-sm table-hover mb-0">
                            <thead class="bg-blue">
                                <tr>
                                    <th class="w-5">#</th>
                                    <th class="w-10">Order date</th>
                                    <th class="w-5">Ref.No</th>
                                    <th class="w-10">Customer</th>
                                    <th class="w-10">Mobile</th>
                                    <th class="w-10">Address</th>
                                    <th class="w-10">Order Details</th>
                                    <th class="w-5">Total Amount</th>
                                    <th class="w-5">Advance Amount</th>
                                    <th class="w-5">Total paid</th>
                                    <th class="w-5">Due</th>
                                    <th class="w-5">Pay </th>
                                    <th class="w-5">View </th>
                                    <th class="w-5">Edit</th>
                                    <th class="w-5"><?= (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) ? 'Restore' : 'Delete'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot class="bg-blue">
                                <tr>
                                    <th class="w-5">#</th>
                                    <th class="w-10">Order date</th>
                                    <th class="w-5">Ref.No</th>
                                    <th class="w-10">Customer</th>
                                    <th class="w-10">Mobile</th>
                                    <th class="w-10">Address</th>
                                    <th class="w-10">Order Details</th>
                                    <th class="w-5">Total Amount</th>
                                    <th class="w-5">Advance Amount</th>
                                    <th class="w-5">Total paid</th>
                                    <th class="w-5">Due</th>
                                    <th class="w-5">Pay </th>
                                    <th class="w-5">View </th>
                                    <th class="w-5">Edit</th>
                                    <th class="w-5"><?= (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) ? 'Restore' : 'Delete'; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>


<?php include 'src/_end.php'; ?>