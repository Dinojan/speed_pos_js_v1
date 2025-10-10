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

    /* Scanner Styles */
    #scanner-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    #scanner-video {
        width: 100%;
        max-width: 500px;
        height: auto;
        min-height: 400px;
        border: 3px solid #fff;
        border-radius: 10px;
        background: #000;
    }

    /* .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 280px;
        height: 280px;
        border: 3px solid #3882ed;
        border-radius: 10px;
        box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.8);
        pointer-events: none;
    } */

    .scanner-controls {
        margin-top: 25px;
        display: flex;
        gap: 15px;
    }

    #scanner-close {
        position: absolute;
        top: 25px;
        right: 25px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 28px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    }

    .scanner-instructions {
        color: white;
        text-align: center;
        margin-top: 20px;
        max-width: 500px;
        font-size: 16px;
    }

    #camera-error {
        color: #ff6b6b;
        text-align: center;
        margin-top: 15px;
        display: none;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px;
        border-radius: 5px;
    }

    .scanning-animation {
        animation: pulse 2s infinite;
    }

    .scanning-animation::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg,
                transparent,
                #3882ed,
                #4CAF50,
                #3882ed,
                transparent);
        animation: laser-scan 2s linear infinite;
    }

    @keyframes laser-scan {
        0% {
            top: 0;
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            top: 100%;
            opacity: 0;
        }
    }

    @keyframes pulse {
        0% {
            border-color: #3882ed;
        }

        50% {
            border-color: #4CAF50;
        }

        100% {
            border-color: #3882ed;
        }
    }

    #scanner-canvas {
        position: absolute;
        top: -10000px;
        left: -10000px;
    }

    .scanner-loading {
        color: white;
        text-align: center;
        margin-bottom: 20px;
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card card-outline card-success">
            <form id="stock-search" class="card-body d-flex" onsubmit="return false;">
                <input type="hidden" name="action_type" value="CHECKED_STOCK">

                <input type="text"
                    class="form-control-lg lg-form-control w-100"
                    placeholder="<?php echo trans('label_scan_/_search_by_barcode_...') ?>"
                    name="search" id="serach_input"
                    required>
                <button type="button" id="btn-scan" class="btn btn-primary ml-2">
                    <i class="fas fa-camera"></i> <span class="d-none d-md-block"><?php echo trans('label_scan') ?></span>
                </button>

                <button type="button" id="btn_check_stock"
                    class="btn btn-success ml-2"
                    style="font-size:20px;">
                    <i class="fas fa-search pr-1"></i> <span class="d-none d-md-block"><?php echo trans('label_find') ?></span>
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

    <!-- Scanner Modal -->
    <div id="scanner-container">
        <button id="scanner-close">
            <i class="fas fa-times"></i>
        </button>
        <!-- <div class="scanner-overlay scanning-animation"></div> -->
        <video id="scanner-video" class="scanner-overlay scanning-animation" autoplay playsinline></video>
        <canvas id="scanner-canvas"></canvas>
        <div id="camera-error"></div>
        <!-- <div class="scanner-controls">
            <button id="switch-camera" class="btn btn-light">
                <i class="fas fa-sync-alt"></i> Switch Camera
            </button>
            <button id="toggle-flash" class="btn btn-light">
                <i class="fas fa-lightbulb"></i> Flash
            </button> -->
    </div>
    <!-- <div class="scanner-instructions">
        <p>Point your camera at a <strong>barcode OR QR code</strong>. The scanner will automatically detect it.</p>
        <p><small>Supported: Code 128, EAN, UPC, Code 39, and QR Codes</small></p>
    </div> -->
</div>

<!-- Rest of your tables remain the same -->
<div class="col-lg-12" ng-show="uncheckedItems.length > 0">
    <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
        <div class="card-header">
            <h2 class="card-title text-lg font-weight-bold"><?= trans('title_selected_jewelries') ?></h2>
            <div class="card-tools"></div>
        </div>
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
    </div>
</div>
<div class="col-lg-12" ng-show="checkedItems.length > 0">
    <div class="card card-primary card-outline" style="height: auto; width: auto; transition: 0.15s;">
        <div class="card-header">
            <h2 class="card-title text-lg font-weight-bold"><?= trans('title_checked_jewelries') ?></h2>
            <div class="card-tools"></div>
        </div>
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
    </div>
</div>
</div>

<!-- Using QuaggaJS for barcode scanning -->
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<?php include 'src/_end.php'; ?>