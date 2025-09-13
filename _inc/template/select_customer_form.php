<form id="select-customer-form" class="form-horizontal" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">

    <div class="form-group">
        <label for="s_id"><?= trans('label_customer'); ?> <i class="text-danger">*</i></label>
        <select id="c_id" name="c_id" class="form-control" required>
            <option value="">-- Select Customer --</option>
            <?php foreach (get_all_customers(1) as $customer): ?>
                <option
                    value="<?= $customer['id'] ?>"
                    data-name="<?= $customer['c_name'] ?>"
                    data-mobile="<?= $customer['c_mobile'] ?>"
                    data-address="<?= $customer['c_address'] ?>">
                    <?= $customer['c_name'] ?> (<?= $customer['c_mobile'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <center>Or Create New</center>

    <div class="form-group">
        <label for="c_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Name">
    </div>
    <div class="form-group">
        <label for="c_mobile"><?= trans('label_mobile'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="c_mobile" name="c_mobile" placeholder="Mobile">
    </div>
    <div class="form-group">
        <label for="c_address"><?= trans('label_address'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="c_address" name="c_address" placeholder="Address">
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="select_customer_btn">
                <i class="fas fa-save"></i> <?= trans('button_save'); ?>
            </button>
        </div>
    </div>
</form>