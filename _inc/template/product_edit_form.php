<form id="update-product-form" class="form-horizontal" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="UPDATE">  
    <input type="hidden" name="id" value="<?=$product['id']?>">  

    <div class="form-group">
        <label for="p_name"><?= trans('label_name'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="p_name" name="p_name" placeholder="Name" required value="<?=$product['p_name']?>">
    </div>
    <div class="form-group">
        <label for="p_code"><?= trans('label_code'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control" id="p_code" name="p_code" placeholder="Code" required value="<?=$product['p_code']?>">
    </div>
    <div class="form-group">
        <label for="c_id"><?= trans('label_category'); ?> <i class="text-danger">*</i></label>
        <select class="form-control select2" id="c_id" name="c_id" required>
            <option value="">-- Select Category --</option>
            <?= set_category_tree_to_select(get_category_tree(),'',$product['c_id']); ?>
           
        </select>
    </div>

    <div class="form-group">
        <label for="s_id"><?= trans('label_supplier'); ?> <i class="text-danger">*</i></label>
        <select class="form-control select2" id="s_id" name="s_id" required>
            <option value="">-- Select Supplier --</option>
            <?php foreach(get_suppliers() as $sup) {?>
            <option value="<?= $sup['id']?>" <?= ($sup['id'] == $product['s_id']) ? 'selected':'' ?>><?= $sup['s_name']?> (<?= $sup['s_mobile']?>)</option> 
            <?php }?>
        </select>
    </div>
    <div class="form-group">
        <label for="wgt"><?= trans('label_weight'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control " id="wgt" name="wgt" placeholder="Weight in (g)" 
        onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value&lt;0){this.value='1';}" 
        onclick="return select()" value="<?=$product['wgt']?>" required="">
    </div>
    <div class="form-group">
        <label for="cost"><?= trans('label_cost'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control " id="cost" name="cost" placeholder="Cost price" onkeypress="return IsNumeric(event);" 
        ondrop="return false;" onpaste="return false;" onkeyup="if(this.value&lt;0){this.value='1';}" onclick="return select()" value="<?=$product['cost']?>" required="">
   
    </div>
    <div class="form-group">
        <label for="qty"><?= trans('label_quantity'); ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control " id="qty" name="qty" placeholder="qty" onkeypress="return IsNumeric(event);" 
        ondrop="return false;" onpaste="return false;" onkeyup="if(this.value&lt;0){this.value='1';}" onclick="return select()" value="<?=$product['qty']?>" required="">
   
    </div>

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="update_product_submit">
                <i class="fas fa-save"></i> <?= trans('button_update'); ?>
            </button>
        </div>
    </div>
</form>