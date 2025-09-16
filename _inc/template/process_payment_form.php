<?php $subtotal = 3000 ?>

<form id="payment-process-form" class="form-horizontal mx-3 mt-3" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">

    <div class="form-group">
        <div class="row">

            <div class="d-flex flex-row align-item-center border-bottom border-top col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_sub_total:") ?></p>
                <input type="text" name="sub_total" class="form-control col-7" value="<?= number_format(($subtotal), 2) ?>">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_other_discount:") ?></p>
                <input type="text" name="discount" class="form-control col-7" value="<?= number_format(($subtotal), 2) ?>">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_final_payment:") ?></p>
                <input type="text" name="final_payment" class="form-control col-7 bg-primary" value="<?= number_format(($subtotal), 2) ?>">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_advance_amount:") ?></p>
                <input type="text" name="advance" class="form-control col-7" value="<?= number_format(($subtotal), 2) ?>">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_to_return_amount:") ?></p>
                <input type="text" name="return" class="form-control col-7" value="<?= number_format(($subtotal), 2) ?>">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_recived:") ?></p>
                <input type="text" name="recived" class="form-control col-7 bg-success" value="<?= number_format(($subtotal), 2) ?>">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_blance:") ?></p>
                <input type="text" name="balance" class="form-control col-7 bg-warning" value="<?= number_format(($subtotal), 2) ?>">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_outstanding:") ?></p>
                <input type="text" name="outstanding" class="form-control col-7 bg-danger" value="<?= number_format(($subtotal), 2) ?>">
            </div>
        </div>
        <div class="mt-5">
            <div class="p-0 d-none">
                <p class="my-2 text-md font-weight-bold"><?= trans("label_paid_by_return_invoice") ?></p>
                <div class="d-flex flex-row position-relative">
                    <div class="col-8 col-md-10 pl-0">
                        <input type="text" class="form-control" placeholder="<?= trans("label_invoice_no_/_return_ID") ?>">
                    </div>
                    <button class="btn btn-primary col-4 col-md-2"><i class="fas fa-search"></i> <?= trans("label_check") ?></button>
                </div>
                <div class="text-danger text-center"></div>
            </div>
            <div class="p-0">
                <p class="my-2 text-md font-weight-bold"><?= trans("label_paid_by_advance_invoice") ?></p>
                <div class="d-flex flex-row position-relative">
                    <div class="col-8 col-md-10 pl-0">
                        <input type="text" class="form-control" placeholder="<?= trans("label_advance_invoice_ref._no") ?>">
                    </div>
                    <button class="btn btn-primary col-4 col-md-2"><i class="fas fa-search"></i> <?= trans("label_check") ?></button>
                </div>
                <div class="text-danger text-center"></div>
            </div>
        </div>
    </div>
</form>