<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Supplier');
$document->setController('SupplierController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h3 class="card-title"><?= trans('title_suppliers') ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" ng-click="openAddSupplierModal()">
                        <i class="fas fa-plus"></i> <?= trans('button_add_supplier') ?>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                      <?php
                    $hide_colums = "";
                    if (user_group_id() != 1) {
                        if (!has_permission('access', 'update_supplier')) {
                            $hide_colums .= "4,";
                        }
                        if (!has_permission('access', 'delete_supplier')) {
                            $hide_colums .= "5,";
                        }
                    }
                    ?>
                    <div class="card-body p-0">
                        <table id="SupplierTable"  data-hide-colums="<?php echo $hide_colums; ?>" class="table table-sm table-hover mb-0">
                            <thead class="bg-blue">
                                <tr>
                                    <th>#</th>
                                    <th>Supplier Name</th>
                                    <th>Mobile</th>
                                    <th>Purchase Jewellery Count</th>
                                    <th>Purchase Cost</th>
                                    <th>Paid Amount</th>
                                    <th>View</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot class="bg-blue">
                                 <tr>
                                    <th>#</th>
                                    <th>Supplier Name</th>
                                    <th>Mobile</th>
                                    <th>Purchase Jewellery Count</th>
                                    <th>Purchase Cost</th>
                                    <th>Paid Amount</th>
                                    <th>View</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
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