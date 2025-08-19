<form id="create-user-form" class="form-row" onsubmit="return false" method="post" enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">

    <div class="form-group col-lg-6">
        <label for="name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="John John" required>
    </div>

    <div class="form-group col-lg-6">
        <label for="email"><?= trans('label_email'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="email" name="email" placeholder="user@example.com" required>
    </div>

    <div class="form-group col-lg-6">
        <label for="phone"><?= trans('label_mobile'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="Ex ( +60123456789 )"
            maxlength="13" onclick="return select()" onkeypress="return IsMobileno(event);" ondrop="return false;"
            onpaste="return false;" required>
    </div>

    <div class="form-group col-lg-6">
        <label for="group_id"><?= trans('label_user_group'); ?> <i class="text-danger">*</i></label>
        <select name="group_id" id="group_id" class="form-control select2 w-100">
            <option value=""><?= trans('label_select_one'); ?></option>
            <?php foreach (get_user_groups() as $group):
                if (user_group_id() != 1):
                    if (user_group_id() == 2):
                        if ($group['group_id'] < 2) continue; ?>
                        <option value="<?= $group['group_id'] ?>"><?= $group['g_name']; ?></option>
                    <?php elseif (user_group_id() == 3):
                        if ($group['group_id'] < 3) continue; ?>
                    <?php endif; ?>
                <?php else:
                    if ($group['group_id'] == 1) continue; ?>
                    <option value="<?= $group['group_id'] ?>"><?= $group['g_name']; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group col-lg-12">
        <label><?= trans('label_company'); ?> <i class="text-danger">*</i></label>
        <div class="row">
            <?php $i = 0;
            foreach (get_stores() as $store): $i++; ?>
                <div class="col-lg-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="store_<?= $i ?>" <?= $store['store_id'] == 1 ? 'checked' : '' ?> name="store[]" value="<?= $store['store_id']; ?>">
                        <label class="form-check-label" for="store_<?= $i ?>"><?= $store['name']; ?></label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group col-lg-6 mx-auto text-center">
        <button type="button" class="btn btn-outline-success" id="create_user_submit">
            <i class="fas fa-save"></i> <?= trans('button_create'); ?>
        </button>
        <button type="reset" id="create_user_reset" class="btn btn-outline-danger ml-3">
            <i class="fas fa-undo"></i> <?= trans('button_reset'); ?>
        </button>
    </div>
</form>
