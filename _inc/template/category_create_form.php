<form id="create-category-form" class="row g-3" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" id="action_type" name="action_type" value="CREATE">

    <!-- Category Name -->
    <div class="col-lg-6">
        <div class="form-group">
            <label for="c_name">
                <?= trans('label_category_name'); ?> <i class="text-danger">*</i>
            </label>
            <input type="text" class="form-control" id="c_name" name="c_name" placeholder="Category Name" required>
        </div>
    </div>

    <!-- Parent Category -->
    <div class="col-lg-6">
        <div class="form-group">
            <label for="p_id">
                <?= trans('label_parent_category'); ?> <i class="text-danger">*</i>
            </label>
            <select name="p_id" id="p_id" class="form-control select2 w-100" required>
                <option value=""><?= trans('label_select_one'); ?></option>
                <?php foreach (get_all_categories(1) as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['c_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Buttons -->
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-outline-success" id="create_category_submit">
            <i class="fas fa-save"></i> <?= trans('button_create'); ?>
        </button>
        <button type="reset" class="btn btn-outline-danger ml-2" id="create_category_reset">
            <i class="fas fa-undo"></i> <?= trans('button_reset'); ?>
        </button>
    </div>
</form>