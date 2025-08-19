<form class="form-row" id="ugroup-del-form" method="post" onsubmit="return false">
  
  <input type="hidden" id="action_type" name="action_type" value="DELETE">
  <input type="hidden" id="id" name="id" value="<?php echo $user_group['group_id']; ?>">

  <div class="col-12">
    <h6 class="text-center"><?= trans('text_delete_from_message'); ?></h6>
  </div>

  <div class="form-group col-md-6 offset-md-3">
    <label for="insert_to" class="col-form-label">
      <?php echo trans('label_insert_content_to'); ?>
    </label>

    <div class="form-check mb-2">
      <input class="form-check-input" type="radio" id="insert_to" value="insert_to" name="delete_action" checked>
      <label class="form-check-label" for="insert_to">
        <?php echo trans('text_select'); ?>
      </label>
    </div>

    <select name="new_group_id" class="form-control select2" aria-label="Select new group">
      <option value=""><?= trans('text_select'); ?></option>
      <?php foreach (get_user_groups() as $group) : ?>
        <?php if ($group['group_id'] == $user_group['group_id']) continue; ?>
        <option value="<?php echo $group['group_id']; ?>">
          <?php echo $group['g_name']; ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="col-12 text-center mt-3">
    <button id="ugroup-delete" class="btn btn-danger btn-sm" data-loading-text="Deleting...">
      <span class="fa fa-fw fa-trash"></span>
      <?php echo trans('button_delete'); ?>
    </button>
  </div>

</form>
