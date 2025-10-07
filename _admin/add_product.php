<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Add product');
$document->setController('AddProductController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h2 class="card-title text-lg font-weight-bold"><?= trans('title_categories') ?></h2>
                <div class="card-tools">
                    <button id="filterBtn" class="btn btn-outline-primary btn-sm" ng-click="toggleFilter()"><?php echo trans("text_newly_added") ?></button>
                    <button type="button" class="btn btn-tool" ng-click="openAddCategoryModal()">
                        <i class="fas fa-plus"></i> <?= trans('button_add_main_category') ?>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- <div class="table-responsive">
                    <div class="card-body p-0">
                        <table id="categoryTable" class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Category Name</th>
                                </tr>
                            </thead>
                            <tbody>
                              
                            </tbody>
                        </table>
                    </div>
                </div> -->
                <div id="category-tree">

                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<?php include 'src/_end.php'; ?>