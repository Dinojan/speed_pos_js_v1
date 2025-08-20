<form id="create-customer-form" class="form-horizontal" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">

    <div class="form-group">
        <label for="c_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Name" required>
    </div>
    <div class="form-group">
        <label for="c_mobile"><?= trans('label_mobile'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="c_mobile" name="c_mobile" placeholder="Mobile" required>
    </div>
     <div class="form-group">
        <label for="c_address"><?= trans('label_address'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="c_address" name="c_address" placeholder="Address" required>
    </div>
        <!-- <button ng-click="suplierAddModal()" class="btn btn-outline-primary">Add</button> -->
   

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="create_customer_submit">
                <i class="fas fa-save"></i> <?= trans('button_create'); ?>
            </button>
            <button type="reset" id="create_customer_reset" class="btn btn-outline-danger ml-3">
                <i class="fas fa-undo"></i> <?= trans('label_reset'); ?>
            </button>
        </div>
    </div>
</form>