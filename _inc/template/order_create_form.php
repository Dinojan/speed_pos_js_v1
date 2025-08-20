<form id="create-order-form" class="form-horizontal" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">
    <div class="form-group">
        <label for="s_id"><?= trans('label_customer'); ?> <i class="text-danger">*</i></label>
        <select class="form-control select2" id="c_id" name="c_id" required>
            <option value="">-- Select Customer --</option>
            <?php foreach (get_all_customers(1) as $cus) { ?>
                <option value="<?= $cus['id'] ?>"><?= $cus['c_name'] ?> (<?= $cus['c_mobile'] ?>)</option>
            <?php } ?>
        </select>
    </div>
    <center>- OR -</center>

    <div class="form-group">
        <label for="cus_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="cus_name" name="cus_name" placeholder="Name" required>
    </div>
    <div class="form-group">
        <label for="cus_mobile"><?= trans('label_mobile'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="cus_mobile" name="cus_mobile" placeholder="Mobile" required>
    </div>
    <div class="form-group">
        <label for="cus_address"><?= trans('label_address'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="cus_address" name="cus_address" placeholder="Address" required>
    </div>
    <div class="form-group">
        <label for="order_details"><?= trans('label_description'); ?> <i class="text-danger">*</i></label>
        <textarea class="form-control" id="order_details" name="order_details" placeholder="Description"
            required></textarea>
    </div>

    <div class="form-group">
        <label for="total_amt"><?= trans('label_total_amount'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="total_amt" name="total_amt" placeholder="Total_Amount" required>
    </div>
    <div class="form-group">
        <label for="advance_amt"><?= trans('label_advance_amount'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="advance_amt" name="advance_amt" placeholder="Advance_Amount"
            required>
    </div>
    <!-- <button ng-click="suplierAddModal()" class="btn btn-outline-primary">Add</button> -->


    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="create_order_submit">
                <i class="fas fa-save"></i> <?= trans('button_create'); ?>
            </button>
            <button type="reset" id="create_order_reset" class="btn btn-outline-danger ml-3">
                <i class="fas fa-undo"></i> <?= trans('label_reset'); ?>
            </button>
        </div>
    </div>
</form>