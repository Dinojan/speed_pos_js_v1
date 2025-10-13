<?php
include("../_init.php");
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle('Stock Checking');
$document->setController('StockAssigningController');

$statement = db()->prepare("SELECT id FROM stock_checking");
$statement->execute();

if ($statement->rowCount() > 0) {
    redirect(root_url() . '/_admin/stock_checking.php');
    exit;
}
?>
<?php include 'src/_top.php'; ?>
<style>
    /* Mobile-specific styles */
    @media (max-width: 767.98px) {
        .category-popover.mobile-view {
            font-size: 14px;
        }

        .category-popover.mobile-view .breadcrumb {
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .category-popover.mobile-view .subcategory-item,
        .category-popover.mobile-view .product-item {
            padding: 8px !important;
            margin-bottom: 6px !important;
        }

        /* Improve touch targets */
        .category-popover.mobile-view .btn,
        .category-popover.mobile-view .subcategory-item {
            min-height: 44px;
        }
    }

    /* Tablet adjustments */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .category-popover {
            min-width: 500px !important;
        }
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
            <div class="card-header">
                <h2 class="card-title text-lg font-weight-bold"><?= trans('title_categories') ?></h2>
                <div class="card-tools"></div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pr-1">
                <div id="category-tree"></div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<?php include 'src/_end.php'; ?>