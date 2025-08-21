<form id="update-order-form" class="form-horizontal" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="UPDATE">
    <input type="hidden" name="id" value="<?= $order['id']?>">
    <div class="form-group">
        <label for="s_id"><?= trans('label_customer'); ?> <i class="text-danger">*</i></label>
        <select class="form-control select2" id="c_id" name="c_id" required>
            <option value="">-- Select Customer --</option>
            <?php foreach (get_all_customers(1) as $cus) { ?>
                <option value="<?= $cus['id'] ?>" <?= $order['cus_id'] == $cus['id'] ? 'selected':''?>><?= $cus['c_name'] ?> (<?= $cus['c_mobile'] ?>)</option>
            <?php } ?>
        </select>
    </div>
    <center>- OR -</center>

    <div class="form-group">
        <label for="cus_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="cus_name" name="cus_name" placeholder="Name"
            value="<?= $order['cus_name'] ?>" required>
    </div>
    <div class="form-group">
        <label for="cus_mobile"><?= trans('label_mobile'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="cus_mobile" name="cus_mobile" placeholder="Mobile"
            value="<?= $order['cus_mobile'] ?>" required>
    </div>
    <div class="form-group">
        <label for="cus_address"><?= trans('label_address'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="cus_address" name="cus_address" placeholder="Address"
            value="<?= $order['cus_address'] ?>" required>
    </div>
    <div class="form-group">
        <label for="order_details"><?= trans('label_description'); ?> <i class="text-danger">*</i></label>
        <textarea class="form-control" id="order_details" name="order_details" placeholder="Description"
             required><?= $order['order_details'] ?> </textarea>
    </div>

    <div class="form-group">
        <label for="total_amt"><?= trans('label_total_amount'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="total_amt" name="total_amt" placeholder="Total_Amount"
            value="<?= $order['total_amt'] ?>" required>
    </div>
    <div class="form-group">
        <label for="advance_amt"><?= trans('label_advance_amount'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="advance_amt" name="advance_amt" placeholder="Advance_Amount"
            value="<?= $order['advance_amt'] ?>" required>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="update_order_submit">
                <i class="fas fa-save"></i> <?= trans('button_update'); ?>
            </button>
            <button type="reset" id="update_order_reset" class="btn btn-outline-danger ml-3">
                <i class="fas fa-undo"></i> <?= trans('label_reset'); ?>
            </button>
        </div>
    </div>
</form>