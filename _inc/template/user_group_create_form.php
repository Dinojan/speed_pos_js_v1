<form id="create-user-group-form" class="form-horizontal" onsubmit="return false" method="post"
      enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">
    
    <div class="form-group">
        <label for="group_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="group_name" name="group_name"
               placeholder="Group Name" required>
    </div>
    
    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="create_user_group_submit">
                <i class="fas fa-save"></i> <?= trans('button_create'); ?>
            </button>
            <button type="reset" id="create_user_group_reset" class="btn btn-outline-danger ml-3">
                <i class="fas fa-undo"></i> <?= trans('label_reset'); ?>
            </button>
        </div>
    </div>
</form>
