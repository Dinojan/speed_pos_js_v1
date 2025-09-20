<?php
// pos v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_check_jewellery_stock'));
$document->setController('CheckStockController');
include('src/_top.php');
?>

<!-- Control Sidebar -->
<?php include('src/_control_sidebar.php'); ?>
<!-- /.control-sidebar -->
<!-- Main Footer -->
<?php // include('src/_footer.php'); 
?>
<style>
    .lg-form-control {
        border: 1px solid #ABABAB;
        outline: none;
        transition: box-shadow 0.3s ease-in-out;
    }

    .lg-form-control:focus {
        border-color: #3882ed;
        box-shadow: 0 0 0 3px #3882ed66;
    }
</style>


<div class="card card-outline card-success">
    <form id="stock-search"
        class="card-body d-flex" onsubmit="return false;">
        <input type="hidden" name="action_type" value="CHECKED_STOCK">

        <div class="col-10 pl-0">
            <input type="text"
                class="form-control-lg lg-form-control w-100"
                placeholder="<?php echo trans('label_scan_/_search_by_barcode_...') ?>"
                name="search" id="serach_input"
                required>
        </div>

        <button type="submit" id="btn_check_stock"
            class="btn btn-success col-2 p-0 font-weight-bold"
            style="font-size:20px;">
            <i class="fas fa-search pr-1"></i>
            <?php echo trans('label_find') ?>
        </button>
    </form>
</div>




<div class="card card-outline card-primary">
    <div class="card-header">
        <h5 class="text-black"><?php echo trans("label_checked_jewellery") ?></h5>
    </div>
    <div class="card-tools"></div>
    <div class="card-body">
        <?php
        $hide_colums = "";
        if (user_group_id() != 1) {
            if (!has_permission('access', 'read_order_invoice')) {
                $hide_colums .= "8,";
            }
            if (!has_permission('access', 'order_payment')) {
                $hide_colums .= "9,";
            }
        }
        ?>
        <div class="table-responsive">
            <table id="checked-jewels-list" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr class="bg-primary">
                        <th><?php echo trans("label_No.") ?></th>
                        <th><?php echo trans("label_date") ?></th>
                        <th><?php echo trans("label_barcode") ?></th>
                        <th><?php echo trans("label_jewelary") ?></th>
                        <th><?php echo trans("label_status") ?></th>
                        <th><?php echo trans("label_checked_count") ?></th>
                        <th><?php echo trans("label_checker") ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="bg-primary">
                        <th><?php echo trans("label_No.") ?></th>
                        <th><?php echo trans("label_date") ?></th>
                        <th><?php echo trans("label_barcode") ?></th>
                        <th><?php echo trans("label_jewelary") ?></th>
                        <th><?php echo trans("label_status") ?></th>
                        <th><?php echo trans("label_checked_count") ?></th>
                        <th><?php echo trans("label_checker") ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
</div>
<?php
include('src/_end.php');
?>