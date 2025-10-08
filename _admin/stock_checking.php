<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Stock Checking');
$document->setController('StockCheckingController');
?>
<?php include 'src/_top.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h2 class="card-title text-lg font-weight-bold"><?= trans('title_selected_jewelries') ?></h2>
                <div class="card-tools"></div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">

            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h2 class="card-title text-lg font-weight-bold"><?= trans('title_checked_jewelries') ?></h2>
                <div class="card-tools"></div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="alert alert-success">hello</div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<?php include 'src/_end.php'; ?>