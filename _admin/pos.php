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

<div class="">
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
            </div>
        </nav>
    </div>
    <div class="d-flex flex-column flex-lg-row mt-4 pt-4">
        <div class="col-lg-5 p-2">
            <form action="" class="card ng-pristine ng-valid" style="height: 93vh;">
                <div class="card-header p-2 bg-primary m-0">
                    <h3 class="p-2"><i class="fas fa-tags"></i> Current Order</h3>
                </div>
                <div class="card-body position-relative" style="max-height: calc(93vh-27.2vh); overflow-y:auto;">
                    <div id="invoice-item-list" class="table-responsive position-relative">
                        <div class="loader-overlay ng-hide" ng-show="isCardTableLoading">
                            <div class="loader">
                                <img src="../assets/az_net/img/nit.gif" alt="Loading...">
                            </div>
                        </div>
                        <table class="table table-hover rounded">
                            <thead style="background: #e7e7e766;">
                                <tr>
                                    <th style="min-width: 150px">Order</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Discount</th>
                                    <th class="text-center">Weight</th>
                                    <th class="text-center">Subtotal</th>
                                    <th></th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                                <tr>
                                    <th class="md-col-5" style="padding-left: 0.5rem; max-width:50vw; min-width: 30%;">
                                        <h6 class="font-weight-bold">22k gold ring</h6>
                                        <p class="text-sm font-weight-normal m-0">Serial No: <?= 'SER1001' ?></p>
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="price" class="text-center text-primary font-weight-bold" style="border: none; max-width:80px; outline: none; background: none;" value="<?= '60,000.00' ?>">
                                    </th>
                                    <th class="text-center p-1" style="gap: 0.5rem;">
                                        <input name="qty" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '1' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="discount" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '0.00' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <input name="weight" class="text-center" style="border: none; min-width:40px; max-width: 80px; outline: none; background: none;" value="<?= '8g' ?>">
                                    </th>
                                    <th class="text-center p-1">
                                        <p><?= '60,000.00' ?></p>
                                    </th>
                                    <th class="text-center p-1"><button class="btn" id="remove-item" style="cursor: pointer;"><i class="fas fa-trash text-danger"></i></button></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer footer footer-md mb-2 table-responsive">
                    <table class="table m-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="d-flex flex-column flex-md-row justify-content-between align-item-center"><span>Customer: <span class="text-primary"><?= 'John Doe' ?></span></span><button class="btn btn-outline-primary"><i class="fas fa-pen pr-1"></i> Change</button></th>
                                <th>Sub Total</th>
                                <th class="text-right" style="padding-right: 1.5rem;">0.00</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>Discount</td>
                                <td class="text-right" style="padding-right: 1.5rem;">0.00</td>
                            </tr>
                            <tr class="bg-success text-white font-weight-bold">
                                <td class="border-right border-light">Total Items: 4 (4)</td>
                                <td class="border-right border-light">Total</td>
                                <td class="text-right" style="padding-right: 1.5rem;">0.00</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="2 d-flex flex-row p-0 mt-3" style="gap: 1rem;">
                        <button class="btn btn-success p-2 py-3 font-weight-normal" style="width: 100%;"><i class="fas fa-money-bill"></i> Process Payment</button>
                        <button class="btn btn-warning py-3 font-weight-normal" style="width: 100%;"><i class="fas fa-pause"></i> Hold Order</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-7 p-2">
            <div class="card" style="height: 93vh;">
                <div class="card-header bg-primary p-3">
                    <h3><i class="far fa-gem"></i> Jewelry Collections</h3>
                </div>
                <div class="card-body" style="overflow-y: auto;">
                    <div class="d-flex flex-row position-relative">
                        <div class="col-8 col-md-10 pl-0">
                            <button class="position-absolute btn text-primary" style="top: 0; left: 0.2rem; height: 100%;"><i class="fas fa-search"></i></button>
                            <input type="text" class="form-control" style="padding-left: 2.5rem;" placeholder="Search by name or barcode...">
                        </div>
                        <button class="btn btn-primary col-4 col-md-2"><i class="fas fa-barcode"></i> Scan</button>
                    </div>
                    <div class="bg-light row">
                        <div class="form-group col-12 col-sm-6 col-md-4 py-2">
                            <label for="categories">Catogory</label>
                            <select name="" id="categories" class="form-control col-12">
                                <option class="dropdown-item" value="#">All Categories</option>
                                <option class="dropdown-item" value="#">Rings</option>
                                <option class="dropdown-item" value="#">Necklaces</option>
                                <option class="dropdown-item" value="#">Earings</option>
                                <option class="dropdown-item" value="#">Bracelets</option>
                                <option class="dropdown-item" value="#">Watches</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-4 py-2">
                            <label for="function-types">Function Type</label>
                            <select name="" id="function-types" class="form-control">
                                <option class="dropdown-item" value="#">All Functions</option>
                                <option class="dropdown-item" value="#">Engagement</option>
                                <option class="dropdown-item" value="#">Wedding</option>
                                <option class="dropdown-item" value="#">Statement</option>
                                <option class="dropdown-item" value="#">Birthstone</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-sm-12 col-md-4 py-2">
                            <label for="materials">Material</label>
                            <select name="" id="materials" class="form-control">
                                <option class="dropdown-item" value="#">All Materials</option>
                                <option class="dropdown-item" value="#">Gold</option>
                                <option class="dropdown-item" value="#">Silver</option>
                                <option class="dropdown-item" value="#">Platinum</option>
                                <option class="dropdown-item" value="#">Diamond</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-warning text-center">Gold</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-2 col-6 col-sm-4 col-md-3 col-xl-2">
                            <div class="card custom-card col-12 p-0" style="cursor: pointer; overflow: hidden;">
                                <!-- <img src="..." class="card-img-top" alt="..."> -->
                                <div class="card-img-top text-center col-12 product-image">
                                    <i class="fas fa-ring text-warning" style="margin: 2.5rem auto; font-size: 5rem;"></i>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-center font-weight-bold m-1">22k children ring</p>
                                    <p class="text-primary text-center font-weight-bold m-1">LKR. 60,000.00</p>
                                    <p class="rounded bg-secondary text-center">Silver</p>
                                </div>
                            </div>
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