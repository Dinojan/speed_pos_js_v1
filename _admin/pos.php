<?php
ob_start();
include realpath(__DIR__ . '/../') . '/_init.php';
if (!is_loggedin()) {
    redirect(root_url() . '/index.php?redirect_to=' . url());
}
$document->setTitle(trans('title_pos'));
$document->setController('PosController');
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
</style>

<div ng-controller="PosController">
    <div class="fixed-top">
        <nav class="navbar navbar-dark bg-dark px-lg-4">
            <a class="btn btn-dark" href="../_admin/dashboard.php"><i class="fas fa-home"></i></a>
            <a href="">SPEED POS >>> <small><?php echo store('name') ?></small></a>
            <div>
                <!-- <button class="btn btn-primary position-relative" ng-click="openHeldOrdersModal()">
                    <i class="fas fa-hand-paper"></i>
                    <span class="position-absolute bg-danger text-white px-1" style="top: -5px; right: -5px; border-radius: 20px;">{{held_orders_count}}</span>
                </button> -->
                <!-- <button class="btn btn-primary"><i class="fas fa-expand-arrows-alt"></i></button>
                <button class="btn btn-primary"><i class="fas fa-sign-out-alt"></i></button> -->
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
        <div class="col-lg-5 pl-2 pr-2 pr-lg-1 item-table mt-5">
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
                                    <th class="text-center"><?= trans("label_price_(8g)") ?></th>
                                    <th class="text-center"><?= trans("label_qty") ?></th>
                                    <th class="text-center"><?= trans("label_dis") ?></th>
                                    <th class="text-center"><?= trans("label_wgt_(g)") ?></th>
                                    <th class="text-center"><?= trans("label_subtotal") ?></th>
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
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" type="text" class="text-center mt-3"
                                            style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;"
                                            ng-model="item.qty" ng-change="updatePayment()">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" type="text" class="text-center mt-3"
                                            style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;"
                                            ng-model="item.discount" ng-change="updatePayment()">
                                    </th>
                                    <th class="text-center p-1 pt-4">{{ item.wgt | number:2 }}</th>
                                    <th class="text-center p-1 pt-4">{{ getSubtotal(item) | number:2 }}</th>
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
                        <thead class="thead-light">
                            <tr>
                                <th class="d-flex flex-column flex-md-row justify-content-between align-items-lg-center">
                                    <span><?= trans("label_customer:") ?> <span class="text-primary">{{ cus.name }} ({{cus.mobile}})</span></span>
                                    <button ng-click="openCustomerModal()" class="btn btn-outline-primary">
                                        <i class="fas fa-pen pr-1"></i> <?= trans("label_change") ?>
                                    </button>
                                    <input type="hidden" id="hidden_c_id" name="cus_id" ng-value="cus.id">
                                    <input type="hidden" id="hidden_c_name" name="cus_name" ng-value="cus.name">
                                    <input type="hidden" id="hidden_c_address" name="cus_address" ng-value="cus.address">
                                    <input type="hidden" id="hidden_c_mobile" name="cus_mobile" ng-value="cus.mobile">
                                </th>
                                <th><?= trans("label_sub_total:") ?></th>
                                <th class="text-right" style="padding-right: 1.5rem;">{{ getTotal() | number:2 }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="d-flex flex-column flex-md-row justify-content-between align-items-lg-center">
                                    <span class="font-weight-bold text"><?= trans("label_ref_/_bill_no:") ?></span>
                                    <input type="text" name="ref" id="order-ref" class="form-control border-primary col-md-3"
                                        ng-model="ref">
                                </td>
                                <td><?= trans("label_discount:") ?></td>
                                <td class="text-right" style="padding-right: 1.5rem;">{{ getTotalDiscount() | number:2 }}</td>
                            </tr>
                            <tr class="bg-success text-white font-weight-bold">
                                <td class="border-right border-light"><?= trans("label_total_items:") ?> {{ getTotalItems() }} of ({{ cart.length }})</td>
                                <td class="border-right border-light"><?= trans("label_final:") ?></td>
                                <td class="text-right" style="padding-right: 1.5rem;">{{ getFinalAmount() | number:2 }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex flex-row p-0 mt-3" style="gap: 1rem;">
                        <button id="payment-process-btn" ng-click="openPaymentProcess()"
                            class="btn btn-success p-2 py-3 font-weight-normal" style="width: 100%;">
                            <i class="fas fa-money-bill"></i> <?= trans("label_process_payment") ?>
                        </button>
                        <!-- <button ng-click="openOrderHoldingModal()" class="btn btn-warning py-3 font-weight-normal" style="width: 100%;">
                            <i class="fas fa-pause"></i> <?php // echo trans("label_hold_order") 
                                                            ?>
                        </button> -->
                    </div>
                </div>
            </form>
        </div>
        <div class="item-container col-lg-7 pl-2 pl-lg-1 pr-2 d-lg-block mt-5" ng-class="{'d-none': !showItems}">
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
                    <!-- <div class="my-2">
                        <div class="form-group col-12 col-sm-6 col-md-4 py-2 px-0">
                            <select name="c_id" id="categorySelect" class="form-control select2"
                                ng-model="selectedCategory" ng-change="onCategoryChange()">
                                <option value="">-- Select Category --</option>
                                <? //  set_category_tree_to_select(get_category_tree(), '') 
                                ?>
                            </select>
                        </div>
                        <div id="products-container" class="row">
                            <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2 card-wrapper"
                                ng-repeat="product in products"
                                ng-click="addToCart(product)">
                                <div class="card custom-card col-12 p-0">
                                    <div class="card-img-top text-center col-12 product-image">
                                        <i class="fas fa-ring text-warning" style="margin:2.5rem auto; font-size:5rem;"></i>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="text-center font-weight-bold m-1">{{ product.p_name }}</p>
                                        <p class="text-primary text-center font-weight-bold m-1">
                                            LKR. {{ product.material_price | number:2 }} (per 8g)
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
                                <div class="p-2 col-6 col-sm-4 col-md-3 card-wrapper"
                                    ng-repeat="product in products"
                                    ng-click="addToCart(product)">
                                    <div class="card custom-card col-12 p-0 ">
                                        <div class="card-body p-3">
                                            <p class="text-center font-weight-bold m-1" style="font-size: 12px;">{{ product.p_name }}</p>
                                            <p class="text-primary text-center font-weight-bold m-1" style="font-size: 12px;">
                                                LKR. {{ product.material_price | number:2 }} (per 8g)
                                            </p>
                                        </div>
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
        <div id="payment-process" class="card position-absolute mt-5 mx-2" style="left: 0; min-height: 90vh;"
            ng-show="showPaymentProcess">
            <div class="d-lg-flex flex-lg-row mt-5">
                <button class="btn position-absolute" style="top: 0; right: 0; z-index: 10000;"
                    ng-click="closePaymentProcess()">
                    <i class="fas fa-times text-danger"></i>
                </button>
                <div class="card-body col-12 col-lg-6 p-3">
                    <?php include(__DIR__ . '/../_inc/template/payment_process_form.php'); ?>
                </div>
                <div class="col-12 col-lg-6 p-0">
                    <div class="card-body px-3 mt-2">
                        <div class="d-flex flex-row justify-content-between" style="gap: 1rem;">
                            <button class="btn btn-outline-primary" ng-click="selectPaymentMethod('cash')"
                                style="width: 100%;"><?= trans("label_cash") ?></button>
                            <button class="btn btn-outline-primary" ng-click="selectPaymentMethod('card')"
                                style="width: 100%;"><?= trans("label_card") ?></button>
                        </div>
                        <p class="text-center my-2">
                            <?= trans("label_payment_method:") ?> <span class="font-weight-bold">{{ paymentMethod }}</span>
                        </p>
                    </div>
                    <div class="keyboard mb-3">
                        <div class="numbers d-flex flex-column col-12" style="gap: 0.5rem;">
                            <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('1')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">1</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('2')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">2</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('3')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">3</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('5000')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5000</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('2000')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 2000</p>
                                </span>
                            </div>
                            <!-- Repeat for other rows (4-6, 7-9, 0-000) as in original -->
                            <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('4')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">4</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('5')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">5</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('6')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">6</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('1000')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 1000</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('500')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 500</p>
                                </span>
                            </div>
                            <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('7')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">7</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('8')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">8</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('9')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">9</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('100')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 100</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('50')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 50</p>
                                </span>
                            </div>
                            <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('0')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">0</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('00')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">00</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('000')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">000</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('20')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 20</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%; cursor: pointer;"
                                    ng-click="appendValue('5')">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5</p>
                                </span>
                            </div>
                        </div>
                        <div class="d-lg-none px-3" style="margin-top: 0.5rem;">
                            <div class="d-flex flex-column" style="gap: 0.5rem;">
                                <div class="d-flex flex-row" style="gap: 0.5rem;">
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content; cursor: pointer;"
                                        ng-click="appendValue('5000')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5000</p>
                                    </span>
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content; cursor: pointer;"
                                        ng-click="appendValue('2000')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 2000</p>
                                    </span>
                                </div>
                                <div class="d-flex flex-row" style="gap: 0.5rem;">
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content; cursor: pointer;"
                                        ng-click="appendValue('1000')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 1000</p>
                                    </span>
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content; cursor: pointer;"
                                        ng-click="appendValue('500')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 500</p>
                                    </span>
                                </div>
                                <div class="d-flex flex-row" style="gap: 0.5rem;">
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content; cursor: pointer;"
                                        ng-click="appendValue('100')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 100</p>
                                    </span>
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content; cursor: pointer;"
                                        ng-click="appendValue('50')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 50</p>
                                    </span>
                                </div>
                                <div class="d-flex flex-row" style="gap: 0.5rem;">
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                        ng-click="appendValue('20')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 20</p>
                                    </span>
                                    <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; cursor: pointer;"
                                        ng-click="appendValue('5')">
                                        <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5</p>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-md-flex flex-row px-3" style="gap: 0.5rem; margin-top: 0.5rem;">
                            <span class="num btn btn-primary pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;"
                                ng-click="fullAmount()">
                                <p class="mt-lg-2" style="font-size: 2rem;"><?= trans("label_full_amount") ?></p>
                            </span>
                            <span class="num btn btn-secondary pb-2 pt-4 pb-lg-4 pt-lg-4 mt-2 mt-md-0" style="width: 100%;"
                                ng-click="clearPayment()">
                                <p class="mt-lg-2" style="font-size: 2rem;"><?= trans("label_clear") ?></p>
                            </span>
                            <span class="num btn btn-success pb-2 pt-4 pb-lg-4 pt-lg-4 mt-2 mt-md-0" style="width: 100%;"
                                ng-click="checkoutOrder()">
                                <p class="mt-lg-2" style="font-size: 2rem;"><?= trans("label_check_out") ?></p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include('src/_end.php');
    ?>