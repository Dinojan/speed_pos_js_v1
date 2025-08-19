<?php
$permissions = array(
    'user_group' => array(
        'read_user_group' => 'Read user group List',
        'create_user_group' => 'Create user group',
        'update_user_group' => 'Update user group',
        'delete_user_group' => 'Delete user group',
    ),
    'user' => array(
        'read_user' => 'Read User List',
        'create_user' => 'Create User',
        'update_user' => 'Update User',
        'delete_user' => 'Delete User',
        'change_password' => 'Change Password',
    ),
); ?>

<form id="update-user-group-form" class="form-horizontal" onsubmit="return false" method="post"
      enctype="multipart/form-data">

    <input type="hidden" id="action_type" name="action_type" value="UPDATE">
    <input type="hidden" id="user_group_id" name="id" value="<?= $user_group['group_id']; ?>">

    <div class="form-group">
        <label for="group_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="group_name" name="group_name" 
               placeholder="Group Name" value="<?= $user_group['g_name']; ?>" required>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" 
                    class="btn btn-outline-success update_user_group_submit" 
                    id="update_user_group_submit">
                <i class="fas fa-save"></i> <?= trans('button_update'); ?>
            </button>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <label><b><?= trans('text_permission'); ?></b></label>
        </div>
    </div>

    <hr>

    <?php $the_permissions = unserialize($user_group['permission']); ?>

    <div class="row p-3">
        <?php foreach ($permissions as $type => $lists): ?>
            <div class="col-lg-3">
                <h6>
                    <input type="checkbox" id="<?= $type; ?>_action" 
                           class="form-check-input"
                           onclick="$('.<?= $type; ?>').prop('checked', this.checked);">
                    <label for="<?= $type; ?>_action" class="form-check-label">
                        <?= ucfirst(str_replace('_', ' ', $type)); ?>
                    </label>
                </h6>
                <hr>
                <?php foreach ($lists as $key => $name): ?>
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input <?= $type; ?>" 
                               id="<?= $key; ?>" value="true"
                               name="access[<?= $key; ?>]" 
                               <?= isset($the_permissions['access'][$key]) ? 'checked' : ''; ?>>
                        <label for="<?= $key; ?>" class="form-check-label">
                            <?= ucfirst($name); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" 
                    class="btn btn-outline-success update_user_group_submit" 
                    id="update_user_group_submit">
                <i class="fas fa-save"></i> <?= trans('button_update'); ?>
            </button>
        </div>
    </div>

</form>
