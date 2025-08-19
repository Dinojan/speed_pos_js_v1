<form id="create-supplier-form" class="form-horizontal" onsubmit="return false" method="post"
      enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">
    
    <div class="form-group">
        <label for="s_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="s_name" name="s_name"
               placeholder="Name" required>
    </div>
    <div class="form-group">
        <label for="s_mobile"><?= trans('label_mobile'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="s_mobile" name="s_mobile"
               placeholder="Mobile" required>
    </div>
    
    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="create_supplier_submit">
                <i class="fas fa-save"></i> <?= trans('button_create'); ?>
            </button>
            <button type="reset" id="create_supplier_reset" class="btn btn-outline-danger ml-3">
                <i class="fas fa-undo"></i> <?= trans('label_reset'); ?>
            </button>
        </div>
    </div>
</form>
