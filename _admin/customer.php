<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Customer');
$document->setController('CustomerController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h3 class="card-title"><?= trans('title_customers') ?>
                    <?php if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
                        echo "( <i class ='fas fa-trash text-danger'> Bin</i>  )";
                    } ?>
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" ng-click="openAddCustomerModal()">
                        <i class="fas fa-plus"></i> <?= trans('button_add_customer') ?>
                    </button>
                    <?php if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
                    ?>
                        <a href="customer.php" type="button" class="btn btn-outline-info btn-sm">
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
                        if (!has_permission('access', 'update_customer')) {
                            $hide_colums .= "7,";
                        }
                        if (!has_permission('access', 'delete_customer')) {
                            $hide_colums .= "8,";
                        }
                    }
                    if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
                        $hide_colums .= "7,";
                    }

                    ?>
                    <div class="card-body p-0">
                        <table id="CustomerTable" data-hide-colums="<?php echo $hide_colums; ?>" class="table table-sm table-hover mb-0">
                            <thead class="bg-blue">
                                <tr>
                                    <th class="w-5">#</th>
                                    <!-- <th class="w-15">Reg No</th> -->
                                    <th class="w-5">Date</th>
                                    <th class="w-20">Customer name</th>
                                    <th class="w-10">Mobile</th>
                                    <th class="w-30">Address</th>
                                    <th class="w-10">Due</th>
                                    <th class="w-5">Pay</th>
                                    <th class="w-5">Profile</th>
                                    <th class="w-5">Edit</th>
                                    <th class="w-5"><?= (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) ? 'Restore' : 'Delete'; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot class="bg-blue">
                                <tr>
                                    <th class="w-5">#</th>
                                    <!-- <th class="w-15">Reg No</th> -->
                                    <th class="w-5">Date</th>
                                    <th class="w-20">Customer name</th>
                                    <th class="w-10">Mobile</th>
                                    <th class="w-30">Address</th>
                                    <th class="w-10">Due</th>
                                    <th class="w-5">Pay</th>
                                    <th class="w-5">Profile</th>
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