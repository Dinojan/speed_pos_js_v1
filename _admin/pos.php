<?php

// pos v2
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
<?php
$products = [
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Gold', 'icon' => 'fas fa-ring', 'color' => 'warning'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Silver', 'icon' => 'fas fa-ring', 'color' => 'secondary'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Gold', 'icon' => 'fas fa-ring', 'color' => 'warning'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Silver', 'icon' => 'fas fa-ring', 'color' => 'secondary'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Gold', 'icon' => 'fas fa-ring', 'color' => 'warning'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Silver', 'icon' => 'fas fa-ring', 'color' => 'secondary'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Gold', 'icon' => 'fas fa-ring', 'color' => 'warning'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Silver', 'icon' => 'fas fa-ring', 'color' => 'secondary'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Gold', 'icon' => 'fas fa-ring', 'color' => 'warning'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Silver', 'icon' => 'fas fa-ring', 'color' => 'secondary'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Gold', 'icon' => 'fas fa-ring', 'color' => 'warning'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Silver', 'icon' => 'fas fa-ring', 'color' => 'secondary'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Gold', 'icon' => 'fas fa-ring', 'color' => 'warning'],
    ['name' => '22k children ring', 'price' => 60000, 'weight' => '8g', 'metal' => 'Silver', 'icon' => 'fas fa-ring', 'color' => 'secondary'],
];

// $customer_model = registry()->get('loader')->modal('customer');

// $the_customers = $customer_model->get_all_customers();
?>
<!-- Control Sidebar -->
<?php // include('src/_control_sidebar.php'); 
?>
<!-- /.control-sidebar -->
<!-- Main Footer -->
<?php // include('src/_footer.php'); 
?>

<style>
    /* .scrollable-table::-webkit-scrollbar {
        display: none;
    } */

    @media (min-width: 992px) {
        .footer {
            min-height: 27.6vh !important;
        }
    }

    @media (min-width: 768px) {
        .footer {
            min-height: 27.6vh !important;
        }
    }

    @media (max-width: 991px) {
        .item-container {
            position: absolute;
            top: 3rem;
            left: 0;
            width: 100%;
            height: 100vh;
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

<div class="" ng-controller="PosController">
    <div class="fixed-top">
        <nav class="navbar navbar-primary bg-primary px-4">
            <a class="btn btn-primary" href="../_admin/dashboard.php"><i class="fas fa-home"></i></a>
            <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> -->
            <div>
                <button class="btn btn-primary position-relative"><i class="fas fa-hand-paper"></i><span class="position-absolute bg-danger text-white px-1" style="top: -5px; right: -5px; border-radius: 20px;">0</span></button>
                <button class="btn btn-primary"><i class="fas fa-expand-arrows-alt"></i></button>
                <button class="btn btn-primary"><i class="fas fa-sign-out-alt"></i></button>
                <button class="btn btn-primary d-lg-none" ng-click="toggleItems()"><i class="fas fa-bars"></i></button>
            </div>
        </nav>
    </div>
    <div class="d-flex flex-column flex-lg-row mt-4 pt-4">
        <div class="col-lg-5 p-2">
            <form action="" class="card ng-pristine ng-valid" style="height: 93vh;" onsubmit="return false" method="post">
                <input type="hidden" id="action_type" name="action_type" value="CREATE">

                <div class="card-header p-2 bg-primary m-0">
                    <h3 class="p-2"><i class="fas fa-tags"></i> <?= trans("label_current_order") ?></h3>
                </div>
                <div class="card-body position-relative" style="max-height: calc(93vh-27.2vh); overflow-y:auto;">
                    <div id="invoice-item-list" class="table-responsive position-relative">
                        <!-- <div class="loader-overlay ng-hide" ng-show="isCardTableLoading">
                            <div class="loader">
                                <img src="../assets/az_net/img/nit.gif" alt="Loading...">
                            </div>
                        </div> -->
                        <table class="table table-hover rounded" id="product-table">
                            <thead style="background: #e7e7e766;">
                                <tr>
                                    <th style="min-width: 150px"><?= trans("label_order") ?></th>
                                    <th class="text-center"><?= trans("label_price") ?></th>
                                    <th class="text-center"><?= trans("label_quantity") ?></th>
                                    <th class="text-center"><?= trans("label_discount") ?></th>
                                    <th class="text-center"><?= trans("label_weight") ?></th>
                                    <th class="text-center"><?= trans("label_subtotal") ?></th>
                                    <th></th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr ng-repeat="item in cart">
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">{{item.name}}<br><small>Serial: {{item.serial}}</small></th>
                                    <th class="text-center p-1">
                                        <input name="price" type="text" class="text-center text-primary font-weight-bold mt-3" style="border: none; max-width:80px; outline: none; background: none;" ng-model="item.price">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" type="text" class="text-center mt-3" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" ng-model="item.qty">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" type="text" class="text-center mt-3" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" ng-model="item.discount">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" type="text" class="text-center mt-3" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" ng-model="item.weight">
                                    </th>
                                    <th class="text-center p-1 pt-4">{{getSubtotal(item) | number:2}} </th>
                                    <th class="text-center p-1 pt-2"><button class="btn mt-2" id="remove-item" style="cursor: pointer;" ng-click="removeItem($index)"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer footer footer-md mb-2 table-responsive">
                    <table class="table m-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="d-flex flex-column flex-md-row justify-content-between align-item-center">
                                    <span><?= trans("label_cutomer:") ?> <span class="text-primary"></span></span>
                                    <button ng-click="openCustomerModal()" class="btn btn-outline-primary"><i class="fas fa-pen pr-1"></i> <?= trans("label_change") ?></button>
                                    <input type="hidden" id="hidden_c_id" name="cus_id">
                                    <input type="hidden" id="hidden_c_name" name="cus_name">
                                    <input type="hidden" id="hidden_c_address" name="cus_address">
                                    <input type="hidden" id="hidden_c_mobile" name="cus_mobile">
                                </th>
                                <th><?= trans("label_sub_total:") ?></th>
                                <th class="text-right" style="padding-right: 1.5rem;">{{getTotal() | number:2}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td><?= trans("label_discount:") ?></td>
                                <td class="text-right" style="padding-right: 1.5rem;">{{getTotalDiscount() | number:2}}</td>
                            </tr>
                            <tr class="bg-success text-white font-weight-bold">
                                <td class="border-right border-light"><?= trans("label_total_items") ?> 4 (4)</td>
                                <td class="border-right border-light"><?= trans("label_final:") ?></td>
                                <td class="text-right" style="padding-right: 1.5rem;">{{ getFinalAmount() | number:2 }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="2 d-flex flex-row p-0 mt-3" style="gap: 1rem;">
                        <button id="payment-process-btn" ng-click="openPaymentProcess()" class="btn btn-success p-2 py-3 font-weight-normal" style="width: 100%;"><i class="fas fa-money-bill"></i> <?= trans("label_process_payment") ?></button>
                        <button class="btn btn-warning py-3 font-weight-normal" style="width: 100%;"><i class="fas fa-pause"></i><?= trans("label_hold_order") ?></button>
                    </div>
                </div>
            </form>
        </div>
        <div class="item-container col-lg-7 p-2 d-lg-block" ng-class="{'d-none': !showItems}">
            <div class="card" style="height: 93vh;">
                <div class="card-header bg-primary p-3 position-relative">
                    <h3><i class="far fa-gem"></i> <?= trans("label_jewelry_collections") ?></h3>
                    <button class="btn d-lg-none position-absolute" style="top: 5px; right: 5px;" ng-click="toggleItems()"><i class="fas fa-times text-white"></i></button>
                </div>
                <div class="card-body" style="overflow-y: auto;">
                    <div class="d-flex flex-row position-relative">
                        <div class="col-8 col-md-10 pl-0">
                            <button class="position-absolute btn text-primary" style="top: 0; left: 0.2rem; height: 100%;"><i class="fas fa-search"></i></button>
                            <input type="text" class="form-control" style="padding-left: 2.5rem;" placeholder="Search by name or barcode...">
                        </div>
                        <button class="btn btn-primary col-4 col-md-2"><i class="fas fa-barcode"></i> <?= trans("label_scan") ?></button>
                    </div>
                    <div class="row my-2">
                        <div class="form-group col-12 col-sm-6 col-md-4 py-2">
                            <label for="categories"><?= trans("label_category") ?></label>
                            <select name="" id="categories" class="form-control col-12">
                                <option class="dropdown-item" value="all-categories"><?= trans("label_all_categories") ?></option>
                                <option class="dropdown-item" value="rings"><?= trans("label_rings") ?></option>
                                <option class="dropdown-item" value="necklaces"><?= trans("label_necklaces") ?></option>
                                <option class="dropdown-item" value="earings"><?= trans("label_earings") ?></option>
                                <option class="dropdown-item" value="bracelets"><?= trans("label_bracelets") ?></option>
                                <option class="dropdown-item" value="watches"><?= trans("label_watches") ?></option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-4 py-2">
                            <label for="function-types"><?= trans("label_function") ?></label>
                            <select name="" id="function-types" class="form-control">
                                <option class="dropdown-item" value="all-functions"><?= trans("label_all_functions") ?></option>
                                <option class="dropdown-item" value="engagement"><?= trans("label_engagement") ?></option>
                                <option class="dropdown-item" value="wedding"><?= trans("label_wedding") ?></option>
                                <option class="dropdown-item" value="statement"><?= trans("label_statement") ?></option>
                                <option class="dropdown-item" value="birthstone"><?= trans("label_birthstone") ?></option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-md-4 py-2">
                            <label for="materials"><?= trans("label_material") ?></label>
                            <select name="" id="materials" class="form-control">
                                <option class="dropdown-item" value="all-material"><?= trans("label_all_material") ?></option>
                                <option class="dropdown-item" value="gold"><?= trans("label_gold") ?></option>
                                <option class="dropdown-item" value="silver"><?= trans("label_silver") ?></option>
                                <option class="dropdown-item" value="platinum"><?= trans("label_platinum") ?></option>
                                <option class="dropdown-item" value="diamond"><?= trans("label_diamond") ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($products as $counter => $product):
                            $id = "product-" . ($counter + 1);
                            $serial = "SER" . (1000 + $counter + 1);
                        ?>
                            <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2 card-wrapper"
                                ng-click="addToCart({
                                    name: '<?= $product['name'] ?>',
                                    price: <?= $product['price'] ?>,
                                    weight: '<?= $product['weight'] ?>',
                                    metal: '<?= $product['metal'] ?>',
                                    icon: '<?= $product['icon'] ?>',
                                    color: '<?= $product['color'] ?>',
                                    serial: '<?= $serial ?>'
                                })">
                                <div class="card custom-card col-12 p-0">
                                    <div class="card-img-top text-center col-12 product-image">
                                        <i class="<?= $product['icon'] ?> text-<?= $product['color'] ?>" style="margin:2.5rem auto; font-size:5rem;"></i>
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="text-center font-weight-bold m-1"><?= $product['name'] ?></p>
                                        <p class="text-primary text-center font-weight-bold m-1">LKR. <?= number_format($product['price'], 2) ?></p>
                                        <p class="rounded bg-<?= $product['color'] ?> text-center"><?= $product['metal'] ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="payment-process" class="card position-absolute" style="left: 0; top: 0; min-height: 100vh;" ng-show="showPaymentProcess">
        <div class="card d-lg-flex flex-lg-row mt-5">
            <button class="btn position-absolute" style="top: 0; right: 0; z-index: 10000;" ng-click="closePaymentProcess()"><i class="fas fa-times text-danger"></i></button>
            <div class="card-body col-12 col-lg-6 p-3">
                <?php include(__DIR__ . '/../_inc/template/process_payment_form.php'); ?>
            </div>
            <div class="col-12 col-lg-6 p-0">
                <div class="card-body px-3 mt-2">
                    <div class="d-flex flex-row justify-content-between" style="gap: 1rem;">
                        <button class="btn btn-outline-primary" style="width: 100%;"><?= trans("label_cash") ?></button>
                        <button class="btn btn-outline-primary" style="width: 100%;"><?= trans("label_card") ?></button>
                        <button ng-click="openChequeDetailsForm()" class="btn btn-outline-primary" style="width: 100%;"><?= trans("label_cheque") ?></button>
                    </div>
                    <p class="text-center my-2"><?= trans("label_payment_method") ?> <span class="font-weight-bold">Cash</span></p>
                </div>
                <div class="keyboard mb-3">
                    <div class="numbers d-flex flex-column col-12" style="gap: 0.5rem;">
                        <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">1</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">2</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">3</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5000</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 2000</p>
                            </span>
                        </div>

                        <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">4</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">5</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">6</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 1000</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 500</p>
                            </span>
                        </div>

                        <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">7</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">8</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">9</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 100</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 50</p>
                            </span>
                        </div>
                        <div class="num-row d-flex flex-row col-12" style="gap: 0.5rem;">
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">0</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">00</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">000</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 20</p>
                            </span>
                            <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4 d-none d-lg-block" style="width: 100%;">
                                <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5</p>
                            </span>
                        </div>
                    </div>
                    <div class="d-lg-none px-3" style="margin-top: 0.5rem;">
                        <div class="d-flex flex-column" style="gap: 0.5rem;">
                            <div class="d-flex flex-row" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5000</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 2000</p>
                                </span>
                            </div>

                            <div class="d-flex flex-row" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 1000</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 500</p>
                                </span>
                            </div>

                            <div class="d-flex flex-row" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 100</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%; height: fit-content;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 50</p>
                                </span>
                            </div>

                            <div class="d-flex flex-row" style="gap: 0.5rem;">
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs.20</p>
                                </span>
                                <span class="num btn btn-dark pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                                    <p class="mt-lg-2" style="font-size: 2rem;">Rs. 5</p>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="d-md-flex flex-row px-3" style="gap: 0.5rem; margin-top: 0.5rem;">
                        <span class="num btn btn-primary pb-2 pt-4 pb-lg-4 pt-lg-4" style="width: 100%;">
                            <p class="mt-lg-2" style="font-size: 2rem;"><?= trans("label_full_amount") ?></p>
                        </span>
                        <span class="num btn btn-secondary pb-2 pt-4 pb-lg-4 pt-lg-4 mt-2 mt-md-0" style="width: 100%;">
                            <p class="mt-lg-2" style="font-size: 2rem;"><?= trans("label_clear") ?></p>
                        </span>
                        <span class="num btn btn-success pb-2 pt-4 pb-lg-4 pt-lg-4 mt-2 mt-md-0" style="width: 100%;">
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