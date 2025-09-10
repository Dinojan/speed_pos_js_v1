<?php
include("../../_init.php");

$order_id = isset($request->get['order_id']) ? $request->get['order_id'] : 0;
$order_model = registry()->get('loader')->model('order');
$the_order = $order_model->getorder($order_id);

// ob_start();
?>

<style type="text/css">
    .modal-lg {
        width: 97%;
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>

<form class="form-horizontal" id="create_payment_form" onsubmit="return false" method="post">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">
    <input type="hidden" name="order_id" value="<?= $the_order['id'] ?>">
    <input type="hidden" name="cus_id" value="<?= $the_order['cus_id'] ?>">
    <input type="hidden" name="order_no" value="<?= $the_order['ref_no'] ?>">

    <div class="card-body">
        <div class="table-selection row" style="width: 100%;">
            <!-- Payment Method List -->


            <!-- Payment Options -->
            <div class="col-lg-6 col-md-12 col-sm-12 bootboox-container pmethod-option checkout-payment-option mt-3">
                <div class="tab-wrapper tab-cheque bootboox-container tab-cheque-payment">

                    <button ng-click="payAmount =balanceAmount; calc(); " type="button" class="btn btn-success full-paid" style="width:100%">
                        <i class="fas fa-money-bill"></i> <?php echo trans('button_full_payment'); ?>
                    </button>

                    <div class="input-group input-group-lg mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text hidden-sm"><?php echo trans('text_pay_amount'); ?></span>
                        </div>
                        <input
                            ng-model="payAmount"
                            ng-keyup="calc()"
                            class="form-control"
                            name="paid_amount"
                            id="pay-amount"
                            value="0"
                            placeholder="<?php echo trans('placeholder_enter_an_amount'); ?>"
                            onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"
                            onkeyup="if(this.value<0){this.value='1';}">
                        <textarea
                            name="note"
                            class="form-control mt-3"
                            id="userMessage"
                            rows="8"
                            style="width:100%; padding:0.5rem;"
                            placeholder="Enter any note"
                            ng-model="note"
                            ></textarea>
                    </div>

                    <div bind-html-compile="rawPaymentMethodHtml"></div>
                </div>
            </div>

            <!-- Billing Details -->
            <div class="col-lg-6 col-md-12 col-sm-12 cart-details bootboox-container mt-3">
                <div class="table-responsive mt-30">
                    <div class="w-40"><?php echo trans('label_order_ID: ') . $the_order['ref_no'] ?></div>
                    <hr style="margin: 0.4rem 0;">
                    <div class="w-40"><?php echo trans('label_customer_name: ') . $the_order['cus_name'] ?></div>
                    <table class="table table-bordered table-striped table-condensed" style="width: 100%; margin-top: 0.5rem;">
                        <tbody class="col-sm-6">
                            <tr class="" style="padding: 0 0.5rem;">
                                <td class="w-50" style="padding-left: 2rem;"><?php echo trans('label_order'); ?></td>
                                <td class="w-50" style="padding-left: 2rem;"><?= $the_order["order_details"] ?></td>
                            </tr>
                            <tr class="" style="padding: 0 0.5rem;">
                                <td class="w-50" style="padding-left: 2rem;"><?php echo trans('label_total_amount'); ?></td>
                                <td class="w-50" style="padding-left: 2rem;"><?= number_format($the_order["total_amt"], 2) ?></td>
                            </tr>
                            <tr class="" style="padding: 0 0.5rem;">
                                <td class="w-50" style="padding-left: 2rem;"><?php echo trans('label_advance'); ?></td>
                                <td class="w-50" style="padding-left: 2rem;"><?= number_format($the_order["advance_amt"], 2) ?></td>
                            </tr>
                            <tr class="" style="padding: 0 0.5rem;">
                                <td class="w-50" style="padding-left: 2rem;"><?php echo trans('label_total_paid'); ?></td>
                                <td class="w-50" style="padding-left: 2rem;"><?= number_format($the_order["total_paid"], 2) ?></td>
                            </tr>
                            <tr class="" style="padding: 0 0.5rem;">
                                <th class="w-50" style="padding-left: 2rem;"><?php echo trans('label_Due'); ?></th>
                                <th class="w-50" style="padding-left: 2rem;"><?= number_format($the_order["total_amt"] - $the_order["total_paid"], 2) ?></th>
                                <input type="hidden" ng-init="balanceAmount=<?= $the_order["total_amt"] - $the_order["total_paid"] ?>" id="due-amount" value="<?= $the_order["total_amt"] - $the_order["total_paid"] ?>">
                            </tr>
                            <tr class="" style="padding: 0 0.5rem;">
                                <td class="w-50" style="padding-left: 2rem;"><?php echo trans('label_paid_amount'); ?></td>
                                <td class="w-50" style="padding-left: 2rem;">{{ payAmount | formatDecimal:2 }}</td>
                            </tr>
                            <tr class="" style="padding: 0 0.5rem;">
                                <th class="w-50" style="padding-left: 2rem;">{{ label }}</th>
                                <th class="w-50" style="padding-left: 2rem;" id="balance">{{ balanceAmount | formatDecimal:2 }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Final Pay Now button -->
    <div class="row">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" id="create_payment_submit" class="btn btn-success btn-flat w-40" ng-click="payNow('No');"><i class="fas fa-money-bill-wave"></i> Pay Now &rarr;</button>
        </div>
    </div>
</form>

<?php
?>