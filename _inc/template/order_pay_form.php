<?php
include("../../_init.php");

$order_id = isset($request->get['order_id']) ? $request->get['order_id'] : 0;

// ob_start();
?>

<style type="text/css">
    .modal-lg {
        width: 97%;
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>

<form class="form-horizontal" id="checkout-form" action="payment.php" method="post">

    <input type="hidden" name="invoice-id" value="{{ order.invoice_id }}">
    <input type="hidden" name="customer-id" value="{{ order.customer_id }}">
    <input type="hidden" name="pmethod-id" value="{{ pmethodId }}">

    <div class="card-body">
        <div class="table-selection row">
            <!-- Payment Method List -->
          

            <!-- Payment Options -->
            <div class="col-lg-5 col-md-12 col-sm-12 bootboox-container pmethod-option checkout-payment-option mt-3">
                <div class="tab-wrapper tab-cheque bootboox-container tab-cheque-payment">

                    <button ng-click="payNowWithFullPaid()" type="button" class="btn btn-success full-paid" style="width:100%">
                        <span class="fa fa-fw fa-money"></span> <?php echo trans('button_full_payment'); ?>
                    </button>

                    <div class="input-group input-group-lg mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text hidden-sm"><?php echo trans('text_pay_amount'); ?></span>
                        </div>
                        <input ng-init="paidAmount=(order.payable_amount - order.due_paid)" 
                               class="form-control"
                               name="paid-amount" 
                               ng-model="paidAmount"
                               placeholder="<?php echo trans('placeholder_input_an_amount'); ?>"
                               onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;"
                               onkeyup="if(this.value<0){this.value='1';}">
                    </div>

                    <div class="mt-5">
                        <input type="text" name="note" class="form-control note" placeholder="<?php echo trans('placeholder_note_here'); ?>">
                    </div>

                    <div bind-html-compile="rawPaymentMethodHtml"></div>
                </div>
            </div>

            <!-- Billing Details -->
            <div class="col-lg-4 col-md-12 col-sm-12 cart-details bootboox-container mt-3">
                <div class="table-responsive mt-30">
                    <table class="table table-bordered table-striped table-condensed table-sm">
                        <tbody>
                            <tr>
                                <td class="w-40 text-right"><?php echo trans('label_invoice_id'); ?></td>
                                <td class="w-60 bg-gray">{{ order.invoice_id }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    <h4><?php echo trans('text_billing_details'); ?></h4>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed table-sm">
                        <tbody>
                            <tr ng-repeat="items in order.items">
                                <td class="text-center w-10">
                                    <input type="hidden" ng-attr-name="item[{{items.item_id}}][item_id]" value="{{ items.item_id }}">
                                    <input type="hidden" ng-attr-name="item[{{items.item_id}}][category_id]" value="{{ items.categoryId }}">
                                    <input type="hidden" ng-attr-name="item[{{items.item_id}}][item_name]" value="{{ items.item_name }}">
                                    <input type="hidden" ng-attr-name="item[{{items.item_id}}][item_price]" value="{{ items.item_price | formatDecimal:2 }}">
                                    <input type="hidden" ng-attr-name="item[{{items.item_id}}][item_quantity]" value="{{ items.item_quantity }}">
                                    <input type="hidden" ng-attr-name="item[{{items.item_id}}][item_total]" value="{{ items.item_total | formatDecimal:2 }}">
                                    {{ $index+1 }}
                                </td>
                                <td class="w-70">{{ items.item_name }} (x{{ items.item_quantity | formatDecimal:2 }} {{ items.unitName }})</td>
                                <td class="text-right w-20">{{ items.item_total | formatDecimal:2 }}</td>
                            </tr>
                        </tbody>

                        <!-- Totals Section -->
                        <tfoot>
                            <tr>
                                <th class="text-right w-60" colspan="2"><?php echo trans('label_subtotal'); ?></th>
                                <input type="hidden" name="sub-total" value="{{ order.subtotal }}">
                                <td class="text-right w-40">{{ order.subtotal | formatDecimal:2 }}</td>
                            </tr>
                            <tr>
                                <th class="text-right w-60" colspan="2"><?php echo trans('label_discount'); ?> {{ discountType == 'percentage' ? '('+discountAmount+'%)' : '' }}</th>
                                <td class="text-right w-40">
                                    {{ discountType == 'percentage' ? (_percentage(totalAmount, discountAmount) | formatDecimal:2) : (discountAmount | formatDecimal:2) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right w-60" colspan="2"><?php echo trans('label_shipping_charge'); ?> {{ shippingType == 'percentage' ? '('+shippingAmount+'%)' : '' }}</th>
                                <input type="hidden" name="shipping-type" value="{{ shippingType }}">
                                <input type="hidden" name="shipping-amount" value="{{ shippingType=='percentage' ? _percentage(totalAmount, shippingAmount) : shippingAmount }}">
                                <td class="text-right w-40">{{ shippingType=='percentage' ? (_percentage(totalAmount, shippingAmount) | formatDecimal:2) : (shippingAmount | formatDecimal:2) }}</td>
                            </tr>
                            <tr>
                                <th class="text-right w-60" colspan="2"><?php echo trans('label_others_charge'); ?></th>
                                <input type="hidden" name="others-charge" value="{{ othersCharge }}">
                                <td class="text-right w-40">{{ othersCharge | formatDecimal:2 }}</td>
                            </tr>
                            <tr>
                                <th class="text-right w-60" colspan="2"><?php echo trans('label_previous_due'); ?></th>
                                <input type="hidden" name="previous-due" value="{{ order.previous_due }}">
                                <td class="text-right w-40">{{ order.previous_due | formatDecimal:2 }}</td>
                            </tr>
                            <tr>
                                <th class="text-right w-60 bg-gray" colspan="2"><?php echo trans('label_payable_amount'); ?> <small>({{ order.total_items }} items)</small></th>
                                <input type="hidden" name="payable-amount" value="{{ order.payable_amount }}">
                                <td class="text-right w-40 bg-gray">{{ order.payable_amount | formatDecimal:2 }}</td>
                            </tr>
                            <tr class="success">
                                <th class="text-right w-60" colspan="2"><?php echo trans('label_previous_due_paid'); ?></th>
                                <input type="hidden" name="previous-due-paid" value="{{ order.prev_due_paid }}">
                                <td class="text-right w-40">{{ order.prev_due_paid | formatDecimal:2 }}</td>
                            </tr>

                            <!-- Payment rows (unchanged logic, just formatting preserved) -->
                            <tr ng-repeat="payments in order.payments" ng-class="{'danger': payments.type=='discount', 'success': payments.type!='discount'}">
                                <th ng-if="payments.type=='discount'" class="text-right w-60" colspan="2">
                                    <small><i>Discount on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small>
                                </th>
                                <td ng-if="payments.type=='discount'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>
                            </tr>

                            <tr ng-repeat="payments in order.payments" ng-class="{'danger': payments.type=='return', 'success': payments.type!='return'}">
                                <th ng-if="payments.type=='due_paid'" class="text-right w-60" colspan="2">
                                    <small><i>Duepaid on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small>
                                </th>
                                <td ng-if="payments.type=='due_paid'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>

                                <th ng-if="payments.type=='sell'" class="text-right w-60" colspan="2">
                                    <small><i>Paid by</i></small> {{ payments.name }} <i>on</i> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small>
                                </th>
                                <td ng-if="payments.type=='sell'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>

                                <th ng-if="payments.type=='return'" class="text-right w-60" colspan="2">
                                    <small><i>Return on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small>
                                </th>
                                <td ng-if="payments.type=='return'" class="text-right w-40">{{ payments.amount | formatDecimal:2 }}</td>
                            </tr>

                            <tr class="danger">
                                <th class="text-right w-60" colspan="2"><?php echo trans('label_due'); ?></th>
                                <input type="hidden" name="due-amount" value="{{ order.due }}">
                                <td class="text-right w-40">{{ order.total_due | formatDecimal:2 }}</td>
                            </tr>

                            <tr ng-repeat="payments in order.payments" class="success">
                                <th ng-if="payments.type=='change'" class="text-right w-60" colspan="2">
                                    <small><i>Change on</i></small> {{ payments.created_at }} <small><i>by {{ payments.by }}</i></small>
                                </th>
                                <td ng-if="payments.type=='change'" class="text-right w-40">{{ payments.pos_balance | formatDecimal:2 }}</td>
                            </tr>

                            <tr ng-show="order.invoice_note" class="active">
                                <td colspan="3">
                                    <b><?php echo trans('label_note'); ?>:</b> {{ order.invoice_note }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Final Pay Now button -->
    <div class="row">
        <div class="col-lg-6 mx-auto text-center">
            <button type="submit" class="btn btn-success btn-flat w-40" ng-click="payNow('No');"><i class="fas fa-money-bill-wave"></i> Pay Now &rarr;</button>
        </div>
    </div>
</form>

<?php
?>
