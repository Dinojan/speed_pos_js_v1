<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Product');
$document->setController('ProductController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h3 class="card-title"><?= trans('title_products') ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" ng-click="openAddProductModal()">
                        <i class="fas fa-plus"></i> <?= trans('button_add_product') ?>
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
                        if (!has_permission('access', 'update_product')) {
                            $hide_colums .= "9,";
                        }
                        if (!has_permission('access', 'delete_product')) {
                            $hide_colums .= "10,";
                        }
                    }
                    ?>
                    <div class="card-body p-0">
                        <table id="ProductTable"  data-hide-colums="<?php echo $hide_colums; ?>" class="table table-sm table-hover mb-0">
                            <thead class="bg-blue">
                                <tr>
                                    <th class="w-5">#</th>
                                    <th class="w-10">Product code</th>
                                    <th class="w-20">Name</th>
                                    <th class="w-15">Category</th>
                                    <th class="w-20">Supplier</th>
                                    <th class="w-5">Weight</th>
                                    <th class="w-5">Cost</th>
                                    <th class="w-5">Status</th>
                                    <th class="w-5">View</th>
                                    <th class="w-5">Edit</th>
                                    <th class="w-5">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot class="bg-blue">
                                 <tr>
                                     <th class="w-5">#</th>
                                    <th class="w-10">Product code</th>
                                    <th class="w-20">Name</th>
                                    <th class="w-15">Category</th>
                                    <th class="w-20">Supplier</th>
                                    <th class="w-5">Weight</th>
                                    <th class="w-5">Cost</th>
                                    <th class="w-5">Status</th>
                                    <th class="w-5">View</th>
                                    <th class="w-5">Edit</th>
                                    <th class="w-5">Delete</th>
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