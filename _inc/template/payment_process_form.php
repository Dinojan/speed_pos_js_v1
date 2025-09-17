<form id="payment-process-form" class="form-horizontal mx-3 mt-3" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="PLACE_ORDER">
    <input type="hidden" id="cus_id" name="cus_id" ng-model="cus.id">
    <input type="hidden" id="cus_name" name="cus_name" ng-model="cus.name">
    <input type="hidden" id="cus_address" name="cus_address" ng-model="cus.address">
    <input type="hidden" id="cus_mobile" name="cus_mobile" ng-model="cus.mobile">
    <input type="hidden" id="payment_method" name="payment_method" ng-model="paymentMethod">
    <input type="hidden" id="ref" name="ref" ng-model="ref">

    <div class="form-group">
        <div class="row">

            <div class="d-flex flex-row align-item-center border-bottom border-top col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_sub_total:") ?></p>
                <p class="form-control col-7 border-secondary">{{ payment.sub_total | number: 2 }}</p>
                <input type="hidden"
                    name="sub_total"
                    ng-model="payment.sub_total">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_other_discount:") ?></p>
                <input type="text"
                    name="total_discount"
                    class="form-control-lg col-7 border-secondary"
                    ng-model="payment.total_discount"
                    ng-focus="setActiveInput('total_discount')"
                    ng-keypress="allowNumbersOnly($event)"
                    style="outline: none; border: 1px solid;"
                    required>

            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_final_payment:") ?></p>
                <p class="form-control col-7 bg-primary border-primary">{{ payment.final_payment | number: 2 }}</p>
                <input type="hidden"
                    name="final_payment"
                    ng-model="payment.final_payment">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_advance_amount:") ?></p>
                <input type="text"
                    name="advance"
                    class="form-control-lg col-7 border-secondary"
                    ng-model="payment.advance"
                    ng-focus="setActiveInput('advance')"
                    ng-change="checkAdvance()"
                    ng-keypress="allowNumbersOnly($event)"
                    style="outline: none; border: 1px solid;"
                    required>
            </div>
            <!-- <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><? // trans("label_to_return_amount:") 
                                                            ?></p>
                <input type="text"
                    name="return"
                    class="form-control col-7"
                    ng-model="payment.return"
                    ng-focus="setActiveInput('return')"
                    ng-keypress="allowNumbersOnly($event)"
                    value="<? // number_format(($subtotal), 2) 
                            ?>">
            </div> -->
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_received:") ?></p>
                <input type="text"
                    name="received"
                    class="form-control-lg col-7 bg-success border-success"
                    ng-model="payment.received"
                    ng-focus="setActiveInput('received')"
                    ng-keypress="allowNumbersOnly($event)"
                    style="outline: none; border: 1px solid;"
                    required>
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_blance:") ?></p>
                <p class="form-control col-7 bg-warning border-warning">{{ payment.balance | number: 2 }}</p>
                <input type="hidden"
                    name="balance"
                    ng-model="payment.balance">
            </div>
            <div class="d-flex flex-row align-item-center border-bottom col-12 m-0 py-3">
                <p class="col-5 m-0 pt-2 font-weight-bold"><?= trans("label_outstanding:") ?></p>
                <p class="form-control col-7 bg-danger border-danger">{{ payment.outstanding | number: 2 }}</p>
                <input type="hidden"
                    name="outstanding"
                    ng-model="payment.outstanding">
            </div>
        </div>
        <div class="mt-5">
            <!-- Not allowed to display ------------------------------------------------------------------------------------------------------------------------------------------ -->
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
            <!-- ----------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
            <div class="p-0">
                <p class="my-2 text-md font-weight-bold"><?php echo trans("label_paid_by_advance_invoice") ?></p>
                <div class="d-flex flex-row position-relative">
                    <div class="col-8 col-md-10 pl-0">
                        <input type="text" class="form-control" placeholder="<?php echo trans("label_advance_invoice_ref._no") ?>">
                    </div>
                    <button class="btn btn-primary col-4 col-md-2"><i class="fas fa-search"></i> <?php echo trans("label_check") ?></button>
                </div>
                <div class="text-danger text-center"></div>
            </div>
        </div>
    </div>
</form>