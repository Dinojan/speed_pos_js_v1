<form id="create-product-form" class="form-horizontal" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">

    <div class="form-group">
        <label for="p_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="p_name" name="p_name" placeholder="Name" required>
    </div>
    <div class="form-group">
        <label for="p_code"><?= trans('label_code'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="p_code" name="p_code" placeholder="Code" required>
    </div>
    <div class="form-group">
        <label for="c_id"><?= trans('label_material'); ?> <i class="text-danger">*</i></label>
        <select class="form-control select2" id="c_id" name="material" required>
            <option value="">-- Select Material --</option>
            <?php foreach (set_materials_to_select() as $material) : ?>
                <option value="<?= $material['id'] ?>"><?= $material['c_name'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="form-group">
        <label for="c_id"><?= trans('label_category'); ?> <i class="text-danger">*</i></label>
        <select class="form-control select2" id="c_id" name="c_id" required>
            <option value="">-- Select Category --</option>
            <?= set_category_tree_to_select(get_category_tree()); ?>
        </select>
    </div>

    <div class="form-group">
        <label for="s_id"><?= trans('label_supplier'); ?> <i class="text-danger">*</i></label>
        <select class="form-control select2" id="s_id" name="s_id" required>
            <option value="">-- Select Supplier --</option>
            <?php foreach (get_suppliers() as $sup) { ?>
                <option value="<?= $sup['id'] ?>"><?= $sup['s_name'] ?> (<?= $sup['s_mobile'] ?>)</option>
            <?php } ?>
        </select>
        <!-- <button ng-click="suplierAddModal()" class="btn btn-outline-primary">Add</button> -->
    </div>
    <div class="form-group">
        <label for="wgt"><?= trans('label_weight'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control " id="wgt" name="wgt" placeholder="Weight in (g)" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">
    </div>
    <div class="form-group">
        <label for="cost"><?= trans('label_cost'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control " id="cost" name="cost" placeholder="Cost price" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">

    </div>
    <div class="form-group d-none">
        <label for="qty"><?= trans('label_quantity'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control " id="qty" name="qty" placeholder="qty" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="1" required="">

    </div>
    <div class="form-group">
        <label for="sts"><?php echo trans("label_status") ?><i class="text-danger">*</i></label>
        <select name="sts" id="sts" class="form-control ">
            <option value="0"><?php echo trans("label_for_sale")?></option>
            <option value="1"><?php echo trans("label_not_for_sale")?></option>
        </select>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="create_product_submit">
                <i class="fas fa-save"></i> <?= trans('button_create'); ?>
            </button>
            <button type="reset" id="create_product_reset" class="btn btn-outline-danger ml-3">
                <i class="fas fa-undo"></i> <?= trans('label_reset'); ?>
            </button>
        </div>
    </div>
</form>