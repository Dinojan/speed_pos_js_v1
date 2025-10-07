<?php
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_sales'));
$document->setController('SalesController');
$document->setBodyClass('sidebar-collapse');
include('src/_top.php');
?>

<style>
    @media (min-width: 992px) {
        .footer {
            min-height: 27.6vh !important;
        }
    }

    @media (min-width: 768px) {
        .footer {
            min-height: 27.6vh !important;
        }

        .item-table {
            position: sticky !important;
            top: 200px;
        }
    }

    @media (max-width: 991px) {
        .item-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 90vh;
            background: #fff;
            transition: left 0.3s ease-in-out;
            z-index: 1000;
        }
    }

    @media (min-width: 576px) {
        .footer {
            min-height: 30vh !important;
        }
    }

    @media (min-width: 0px) {
        .footer {
            min-height: 36vh;
        }
    }

    .custom-card {
        width: 18rem;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }

    .custom-card:hover {
        cursor: pointer;
        transform: translateY(-5px);
        box-shadow: 0 5px 10px 0 #00000066;
    }

    .product-image {
        height: 120px;
        background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold);
        font-size: 2.5rem;
        padding: 10px;
    }

    /* Custom styles for popover animations */
    #item-data[popover] {
        /* Initial hidden state */
        visibility: hidden;
        opacity: 0;
        transform: translateY(-10px);
        /* Adjust based on desired drop distance */

        /* Set the transition properties for all animations */
        transition: opacity 0.3s ease-out,
            transform 0.3s ease-out,
            visibility 0.3s ease-out;

        /* Basic positioning and z-index for popover behavior */
        position: absolute;
        /* Default popover position */
        z-index: 1060;
        /* Higher than Bootstrap's popover, but within its system */
    }

    /* State when the popover is open (fade-in and drop) */
    #item-data[popover]:popover-open {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
        /* Drop into place */
    }

    .item-btn:focus .custom-card {
        outline: none;
        box-shadow: 0 0 12px 3px #3882ed;
        border-radius: 10px;
    }
</style>

<div>
    <div class="fixed-top">
        <nav class="navbar navbar-dark bg-dark px-lg-4">
            <a class="btn btn-dark" href="../_admin/dashboard.php"><i class="fas fa-home"></i></a>
            <a href="">SPEED POS >>> <small><?php echo store('name') ?></small></a>
            <div>
                <button class="btn btn-dark">
                    <a class="text-white" data-widget="fullscreen" id="fullscreen-trigger" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </button>
                <button class="btn btn-dark">
                    <a class="text-white" href="logout.php" role="button" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </button>
                <button class="btn btn-dark d-lg-none" ng-click="toggleItems()"><i class="fas fa-bars"></i></button>
            </div>
        </nav>
    </div>

    <div class="d-flex flex-column flex-lg-row">
        <div class="col-lg-4 pl-2 pr-2 pr-lg-1 item-table mt-5">
            <form action="" class="card mb-0" style="height: 88vh;" onsubmit="return false" method="post">
                <input type="hidden" id="action_type" name="action_type" value="CREATE">
                <div class="card-header pl-2 bg-primary m-0">
                    <h5 class="p-2"><i class="fas fa-tags"></i> <?= trans("label_current_order") ?></h5>
                </div>
                <div class="card-body position-relative" style="max-height: calc(93vh-27.2vh); overflow-y:auto;">
                    <div id="invoice-item-list" class="table-responsive position-relative">
                        <table class="table table-hover rounded" id="product-table">
                            <thead style="background: #e7e7e766;">
                                <tr style="font-size: 12px;">
                                    <th style="min-width: 150px"><?= trans("label_order") ?></th>
                                    <th class="text-center"><?= trans("label_sold_price") ?></th>
                                    <th class="text-center"><?= trans("label_wgt_(g)") ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="item in cart track by $index">
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        {{item.p_name}}<br><small>Serial: {{item.p_code}}</small>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" type="text" class="text-center text-primary font-weight-bold mt-3"
                                            style="border: none; max-width:80px; outline: none; background: none;" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onclick="return select()"
                                            ng-model="item.material_price" ng-change="updatePayment()">
                                    </th>
                                    <th class="text-center p-1 pt-4">{{ item.wgt | number:2 }}</th>
                                    <th class="text-center p-1 pt-2">
                                        <button class="btn mt-2" id="remove-item" style="cursor: pointer;"
                                            ng-click="removeItem($index)"><i class="fas fa-trash text-danger"></i></button>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer footer footer-md mb-2 table-responsive">
                    <table class="table m-0">
                        <tbody>
                            <tr>
                                <td class="d-flex flex-column flex-md-row justify-content-between align-items-lg-center">
                                    <span class="font-weight-bold text"><?= trans("label_ref_/_bill_no:") ?></span>
                                    <input type="text" name="ref" id="order-ref" class="form-control border-primary col-md-3"
                                        ng-model="ref">
                                </td>
                            </tr>
                            <tr class="bg-success text-white font-weight-bold">
                                <td class="border-right border-light d-flex flex-row justify-content-between">
                                    <span class="col-8"><?= trans("label_total_items:") ?> {{ getTotalItems() }} of ({{ cart.length }})</span>
                                    <span class="col-4 d-flex justify-content-between">
                                        <span class="col-4"><?= trans("label_final:") ?></span>
                                        <span class="col-8 text-right">{{ getTotal() | number:2 }}</span></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex flex-row p-0 mt-3" style="gap: 1rem;">
                        <button id="submit-bill-process-btn" ng-click="clickProcessBtn()"
                            class="btn btn-success p-2 py-3 font-weight-normal" style="width: 100%;">
                            <i class="fas fa-save"></i> <?= trans("label_submit") ?>
                        </button>
                        <button id="cancel-bill-process-btn" ng-click="clickCancelBtn()"
                            class="btn btn-danger p-2 py-3 font-weight-normal" style="width: 100%;">
                            <i class="fas fa-undo"></i> <?= trans("label_cancel_(esc)") ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="item-container col-lg-8 pl-2 pl-lg-1 pr-2 d-lg-block mt-5" ng-class="{'d-none': !showItems}">
            <div class="card" style="height: 88vh;">
                <div class="card-header bg-primary pl-1 position-relative">
                    <h5 class="p-2"><i class="far fa-gem"></i> <?= trans("label_jewelry_collections") ?></h5>
                    <button class="btn d-lg-none position-absolute" style="top: 5px; right: 5px;"
                        ng-click="toggleItems()"><i class="fas fa-times text-white"></i></button>
                </div>
                <div class="card-body" style="overflow-y: auto;">
                    <div class="d-flex flex-row position-relative mx-2" ng-if="searchOption === 'name'">
                        <div class="col-8 col-md-10 pl-0">
                            <button class="position-absolute btn text-primary" style="top: 0; left: 0.2rem; height: 100%;">
                                <i class="fas fa-search"></i>
                            </button>
                            <input type="text" class="form-control" style="padding-left: 2.5rem;"
                                placeholder="Search by name or barcode..." id="name-or-barcode"
                                ng-model="nameOrBarcode" ng-change="getProductByNameOrBarcode()">
                        </div>
                        <button class="btn btn-primary col-4 col-md-2" ng-click="toggleSearchOption()">
                            <i class="fas fa-barcode"></i> <?= trans("label_scan") ?>
                        </button>
                    </div>
                    <div class="d-flex flex-row position-relative mx-2" ng-if="searchOption === 'barcode'">
                        <div class="col-8 col-md-10 pl-0">
                            <button class="position-absolute btn text-primary" style="top: 0; left: 0.2rem; height: 100%;">
                                <i class="fas fa-barcode"></i>
                            </button>
                            <input type="text" class="form-control" style="padding-left: 2.5rem;"
                                placeholder="Scan barcode..." id="barcode"
                                ng-model="barcode" ng-change="getProductByBarcode()">
                        </div>
                        <button class="btn btn-primary col-4 col-md-2" ng-click="toggleSearchOption()">
                            <i class="fas fa-search"></i> <?= trans("label_search") ?>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 position-relative" style="height: 100%;">
                            <!-- Sidebar -->
                            <div class="sidebar" style="height: 100%;">
                                <!-- SidebarSearch Form -->
                                <div class="form-inline">
                                    <div class="input-group" data-widget="sidebar-search">
                                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                                        <div class="input-group-append">
                                            <button class="btn btn-sidebar">
                                                <i class="fas fa-search fa-fw"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sidebar Menu -->
                                <nav class="mt-2">
                                    <?php echo render_sidebar(get_category_tree()) ?>
                                </nav>
                            </div>
                            <span class="d-none d-lg-block position-absolute" style="width: 1px; min-height: 71vh; background-color: #3882ed; right: 0; top: 0;" class=""></span>
                        </div>
                        <div id="products-container" class="col-lg-10">
                            <!-- products list -->
                            <div class="row">
                                <button class="p-2 col-6 col-sm-4 col-md-3 btn item-btn"
                                    ng-repeat="product in products"
                                    data-popover="{{ product | json }}"
                                    popovertarget="item-data"
                                    popovertargetaction="show"
                                    ng-click="showPopover($event)">
                                    <div class="card custom-card col-12 p-0" style="background-color: #3882ed22; backdrop-filter: blur(16px);">
                                        <div class="card-body p-3">
                                            <p class="text-center font-weight-bold m-1" style="font-size: 15px;">{{ product.p_name }}</p>
                                            <!-- <p class="text-primary text-center font-weight-bold m-1" style="font-size: 15px;">
                                                LKR. {{ product.material_price | number:2 }} (per 8g)
                                            </p> -->
                                        </div>
                                    </div>
                                </button>
                                <div popover id="item-data" class="border rounded card-body p-4" style="background-color: #00000066; backdrop-filter: blur(16px);">
                                    <button popovertarget="item-data" popovertargetaction="hide" class="btn position-absolute" style="top: 0; right: 0;"><i class="fas fa-times text-danger text-lg"></i></button>
                                    <div class="table-responsive">
                                        <table>
                                            <thead>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_name") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">{{ popoverData.p_name }}</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_barcode_/_QR_code") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">{{ popoverData.p_code }}</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_material") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">{{ popoverData.material_name }}</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_weight") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">{{ popoverData.wgt }} g</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_category") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">{{ popoverData.category_name }}</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_supplier") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">{{ popoverData.supplier.s_name }}</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_cost") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">Rs. {{ popoverData.cost | number: 2 }}</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_note") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">{{ popoverData.note || '-' }}</th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom" style="vertical-align: middle;"><?php echo trans("label_created_at") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">
                                                        <div>
                                                            {{ popoverData.created_at | date:'d MMM, yyyy' }}
                                                        </div>
                                                        <div>
                                                            {{ popoverData.created_at | date:'hh:mm a' }}
                                                        </div>
                                                    </th>
                                                </tr>
                                                <tr class="text-white">
                                                    <th class="col-6 pl-2 py-2 border-bottom"><?php echo trans("label_status") ?></th class="text-white">
                                                    <th class="col-6 text-right pr-2 py-2 border-bottom">
                                                        <p class="badge m-0"
                                                            ng-class="{
                                                                'badge-success': popoverData.status == 0,
                                                                'badge-warning': popoverData.status == 1,
                                                                'badge-danger' : popoverData.status == 2,
                                                                'badge-secondary'   : popoverData.status == 3
                                                            }">
                                                            {{ popoverData.status == 0 ? 'for sale' :
                                                                popoverData.status == 1 ? 'not for sale' :
                                                                popoverData.status == 2 ? 'deleted' :
                                                                popoverData.status == 3 ? 'sold' : 'unknown' }}
                                                        </p>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <form action="" class="card-body px-0 overflow-hidden" onsubmit="return false" ng-submit="addToCart(popoverData)">
                                            <input type="text"
                                                ng-model="popoverData.sold_price"
                                                name="price"
                                                class="form-control"
                                                placeholder="Sold price"
                                                onpaste="return false"
                                                ondrop="return false"
                                                ng-keypress="handlePriceKey($event)">
                                            <div class="row">
                                                <button type="button" ng-click="addToCart(popoverData)"
                                                    class="btn btn-primary btn-sm col-6 mx-auto mt-2"
                                                    popovertarget="item-data" popovertargetaction="toggle">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- empty message -->
                            <div class="col-12 text-center p-3" ng-if="products.length === 0">
                                <p class="text-danger font-weight-bold">-- Item not found --</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include('src/_end.php');
    ?>