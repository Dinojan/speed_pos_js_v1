<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Category');
$document->setController('CategoryController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h3 class="card-title"><?= trans('title_categorys') ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" ng-click="openAddCategoryModal()">
                        <i class="fas fa-plus"></i> <?= trans('button_add_category') ?>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <div class="card-body p-0">
                        <table id="categoryTable" class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Category Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-2">1</div>
                                            <div class="col-lg-4">C name</div>
                                            <div class="col-lg-2">0g</div>
                                            <div class="col-lg-4 text-right">
                                                <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                                <a href="#" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>


<?php include 'src/_end.php'; ?>