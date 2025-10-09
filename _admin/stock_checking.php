<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}

$statement = db()->prepare("SELECT id FROM stock_checking");
$statement->execute();

if ($statement->rowCount() == 0) {
    redirect(root_url() . '/_admin/stock_assinging.php');
    exit;
}

$document->setTitle('Stock Checking');
$document->setController('StockCheckingController');
?>
<?php include 'src/_top.php'; ?>
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
<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline card-success">
            <form id="stock-search"
                class="card-body d-flex" onsubmit="return false;">
                <input type="hidden" name="action_type" value="CHECKED_STOCK">

                <div class="col-8 col-lg-10 pl-0">
                    <input type="text"
                        class="form-control-lg lg-form-control w-100"
                        placeholder="<?php echo trans('label_scan_/_search_by_barcode_...') ?>"
                        name="search" id="serach_input"
                        required>
                </div>

                <button type="button" id="btn_check_stock"
                    class="btn btn-success col-4 col-lg-2 p-0 font-weight-bold"
                    style="font-size:20px;">
                    <i class="fas fa-search pr-1"></i>
                    <?php echo trans('label_find') ?>
                </button>
            </form>

            <div class="d-flex flex-column flex-lg-row justify-content-center mb-3 p-4" style="gap: 0.5rem;">
                <div class="alert alert-success text-lg font-weight-bold rounded-pill py-1 px-4 my-0">
                    Checked {{ TotalCheckedItems }} out of {{ totalItems }} items
                </div>
                <button ng-click="clearSelectedItems()" id="clearSelectedItems" class="btn btn-danger text-lg font-weight-bold rounded-pill py-1 px-4"><?php echo trans("label_clear_checking_(esc)") ?></button>
            </div>
        </div>
    </div>
    <div class="col-lg-12" ng-show="uncheckedItems.length > 0">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h2 class="card-title text-lg font-weight-bold"><?= trans('title_selected_jewelries') ?></h2>
                <div class="card-tools"></div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="slectedItems" class="table table-sm table-bordered table-striped">
                        <thead class="bg-primary">
                            <tr>
                                <td><?php echo trans("label_no.") ?></td>
                                <td><?php echo trans("label_code") ?></td>
                                <td><?php echo trans("label_name") ?></td>
                                <td><?php echo trans("label_weight") ?></td>
                                <td><?php echo trans("label_cost") ?></td>
                                <td><?php echo trans("label_note") ?></td>
                                <td><?php echo trans("label_checked_status") ?></td>
                                <td><?php echo trans("label_item_status") ?></td>
                            </tr>
                        </thead>
                        <tfoot class="bg-primary">
                            <tr>
                                <td><?php echo trans("label_no.") ?></td>
                                <td><?php echo trans("label_code") ?></td>
                                <td><?php echo trans("label_name") ?></td>
                                <td><?php echo trans("label_weight") ?></td>
                                <td><?php echo trans("label_cost") ?></td>
                                <td><?php echo trans("label_note") ?></td>
                                <td><?php echo trans("label_checked_status") ?></td>
                                <td><?php echo trans("label_item_status") ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="col-lg-12" ng-show="checkedItems.length > 0">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h2 class="card-title text-lg font-weight-bold"><?= trans('title_checked_jewelries') ?></h2>
                <div class="card-tools"></div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="checkedItems" class="table table-sm table-bordered table-striped">
                        <thead class="bg-primary">
                            <tr>
                                <td><?php echo trans("label_no.") ?></td>
                                <td><?php echo trans("label_code") ?></td>
                                <td><?php echo trans("label_name") ?></td>
                                <td><?php echo trans("label_weight") ?></td>
                                <td><?php echo trans("label_cost") ?></td>
                                <td><?php echo trans("label_note") ?></td>
                                <td><?php echo trans("label_checked_status") ?></td>
                                <td><?php echo trans("label_item_status") ?></td>
                            </tr>
                        </thead>
                        <tfoot class="bg-primary">
                            <tr>
                                <td><?php echo trans("label_no.") ?></td>
                                <td><?php echo trans("label_code") ?></td>
                                <td><?php echo trans("label_name") ?></td>
                                <td><?php echo trans("label_weight") ?></td>
                                <td><?php echo trans("label_cost") ?></td>
                                <td><?php echo trans("label_note") ?></td>
                                <td><?php echo trans("label_checked_status") ?></td>
                                <td><?php echo trans("label_item_status") ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<?php include 'src/_end.php'; ?>