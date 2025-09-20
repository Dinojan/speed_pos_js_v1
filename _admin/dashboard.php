<?php
// dashboard v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_dashboard'));
$document->setController('DashboardController');
include('src/_top.php');
?>

<div class="wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 card p-2 d-flex align-items-center justify-content-between">
                <span class="font-weight-bold"
                    style="font-size: 1.3rem; color: rgb(140, 140, 140); font-weight: 500;">
                    Welcome to <?php echo store('name'); ?>
                </span>
                <img src="../assets/nit/img/<?= (store('logo')) ? store('logo') : 'nit.png'; ?>" alt="logo"
                    width="100px" style="opacity: .8;">
            </div>
        </div>
    </div>


</div>
<?php
include('src/_end.php');
?>