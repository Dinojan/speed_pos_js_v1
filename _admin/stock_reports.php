<?php
// pos v2
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_stock_reports'));
$document->setController('StockReportsController');
include('src/_top.php');
?>

<!-- Control Sidebar -->
<?php include('src/_control_sidebar.php'); ?>
<!-- /.control-sidebar -->
<!-- Main Footer -->
<?php // include('src/_footer.php'); 
?>

<style>
    .btn-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .btn-container button:last-child {
        grid-column: 1 / -1;
    }

    @media (min-width: 768px) {
        .btn-container {
            grid-template-columns: repeat(5, 1fr);
        }

        .btn-container button:last-child {
            grid-column: auto;
        }
    }
</style>

<div class="card bg-light">
    <div class="btn-container card-body px-2 pt-2 pb-0">
        <button class="btn px-5" ng-class="selectedStatus=='all' ? 'btn-primary' : 'btn-secondary'" ng-click="selectProductStatus('all')"><?php echo trans("label_all") ?></button>
        <button class="btn px-5" ng-class="selectedStatus=='0' ? 'btn-primary' : 'btn-secondary'" ng-click="selectProductStatus('0')"><?php echo trans("label_in_stock") ?></button>
        <button class="btn px-5" ng-class="selectedStatus=='3' ? 'btn-primary' : 'btn-secondary'" ng-click="selectProductStatus('3')"><?php echo trans("label_sold") ?></button>
        <button class="btn px-5" ng-class="selectedStatus=='1' ? 'btn-primary' : 'btn-secondary'" ng-click="selectProductStatus('1')"><?php echo trans("label_not_for_sale") ?></button>
        <button class="btn px-5" ng-class="selectedStatus=='2' ? 'btn-primary' : 'btn-secondary'" ng-click="selectProductStatus('2')"><?php echo trans("label_deleted") ?></button>
    </div>
</div>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">
            <?php echo trans('text_stock_report'); ?>
        </h3>
        <div class="card-tools">

        </div>
    </div>
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
            <table id="stock-report" class="table table-sm table-bordered table-striped" data-hide-colums="<?php echo $hide_colums; ?>">
                <thead class="bg-primary">
                    <tr>
                        <th><?php echo trans("label_#") ?></th>
                        <th><?php echo trans("label_barcode") ?></th>
                        <th><?php echo trans("label_name") ?></th>
                        <th><?php echo trans("label_material") ?></th>
                        <th><?php echo trans("label_karate") ?></th>
                        <th><?php echo trans("label_category") ?></th>
                        <th><?php echo trans("label_supplier") ?></th>
                        <th><?php echo trans("label_cost") ?></th>
                        <th><?php echo trans("label_weight") ?></th>
                        <th><?php echo trans("label_quantity") ?></th>
                        <th><?php echo trans("label_status") ?></th>
                        <th><?php echo trans("label_view") ?></th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot class="bg-primary">
                    <tr>
                        <th><?php echo trans("label_#") ?></th>
                        <th><?php echo trans("label_barcode") ?></th>
                        <th><?php echo trans("label_name") ?></th>
                        <th><?php echo trans("label_material") ?></th>
                        <th><?php echo trans("label_karate") ?></th>
                        <th><?php echo trans("label_category") ?></th>
                        <th><?php echo trans("label_supplier") ?></th>
                        <th><?php echo trans("label_cost") ?></th>
                        <th><?php echo trans("label_weight") ?></th>
                        <th><?php echo trans("label_quantity") ?></th>
                        <th><?php echo trans("label_status") ?></th>
                        <th><?php echo trans("label_view") ?></th>
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