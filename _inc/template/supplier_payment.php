<form id="supplier-payment-form" onsubmit="return false">
    <input type="hidden" name="action_type" value="SUPPLIER_PAYMENT">
    <div class="row px-2">
        <div class="col-9 pl-0 pr-2">
            <input type="text" id="supplier_payment" class="form-control" name="payment" placeholder="Payment ..." required>
        </div>
        <button type="submit" id="supplier_payment_submit" class="btn btn-success col-3">
            <i class="fas fa-save"></i> <?php echo trans("label_pay_now") ?>
        </button>
    </div>
</form>