<style>
    .nav-dot {
        width: 12px;
        height: 12px;
        background: #dee2e6;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .nav-dot.active {
        background: var(--bs-primary);
        transform: scale(1.2);
        background: #3882ed;
    }

    .progress-bar {
        transition: width 0.6s ease-in-out;
    }
</style>
<form id="create-product-form" class="form-horizontal" onsubmit="return false" method="post"
    enctype="multipart/form-data">
    <input type="hidden" id="action_type" name="action_type" value="CREATE">
    <!-- <input type="hidden" id="name" name="name" value="{{ productName }}"> -->
    <!-- <input type="hidden" id="action_type" name="action_type" value=""> -->


    <div class="text-center">
        <h5 class="text-success font-weight-bolder">NJ Serial ID: {{ code }}</h5>
        <h5 class="text-danger font-weight-bolder">Path: {{ path }}</h5>
    </div>


    <!-- <div class="form-group">
        <label for="p_name"><?php // echo trans('label_name'); 
                            ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control typeable" id="p_name" name="p_name" placeholder="Name" required>
    </div> -->
    <!-- <div class="form-group">
        <label for="p_code"><?php // echo trans('label_code'); 
                            ?> <i class="text-danger">*</i></label>
        <input type="text" class="form-control typeable" id="p_code" name="p_code" placeholder="Code" required>
    </div> -->
    <!-- <div class="form-group">
        <label for="c_id"><?php // echo trans('label_material'); 
                            ?> <i class="text-danger">*</i></label>
        <select class="form-control typeable select2" id="c_id" name="material" required>
            <option value="">-- Select Material --</option>
            <?php // foreach (set_materials_to_select() as $material) : 
            ?>
                <option value="<?php // echo $material['id'] 
                                ?>"><?php // echo $material['c_name'] 
                                    ?></option>
            <?php // endforeach 
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="c_id"><?php // echo trans('label_category'); 
                            ?> <i class="text-danger">*</i></label>
        <select class="form-control typeable select2" id="c_id" name="c_id" required>
            <option value="">-- Select Category --</option>
            <?php // echo set_category_tree_to_select(get_category_tree()); 
            ?>
        </select>
    </div> -->


    <!-- Progress Bar -->
    <div class="progress mb-3 rounded-pill" style="height: 6px;">
        <div class="progress-bar" id="form-progress" role="progressbar" style="width: 50%;"
            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <!-- Nav Dots -->
    <div class="form-navigation d-flex justify-content-center mb-3" style="gap: 0.5rem;">
        <div class="nav-dot active" data-page="1"></div>
        <div class="nav-dot" data-page="2"></div>
    </div>

    <div class="" id="page-1">
        <div class="form-group">
            <label for="wgt"><?php echo trans('label_weight_(g)'); ?> <i class="text-danger">*</i></label>
            <input type="text" class="form-control typeable " id="wgt" name="wgt" placeholder="Weight (g)" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">
        </div>
        <div class="form-group">
            <label for="karate"><?php echo trans('label_karate'); ?> <i class="text-danger">*</i></label>
            <input type="text" class="form-control typeable " id="karate" name="karate" placeholder="Karate" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">

        </div>
        <div class="form-group">
            <label for="s_id"><?php echo trans('label_supplier'); ?><i class="text-danger">*</i></label>
            <select class="form-control select2" id="s_id" name="s_id" required>
                <option value="">-- Select Supplier --</option>
                <?php foreach (get_suppliers() as $sup) { ?>
                    <option value="<?php echo $sup['id'] ?>" <?php echo ($sup['id'] == 1) ? 'selected' : ''; ?>>
                        <?php echo $sup['s_name'] ?> (<?php echo $sup['s_mobile'] ?>)
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="purchase"><?php echo trans('label_purchase'); ?> <i class="text-danger">*</i></label>
            <input type="text" class="form-control typeable " id="purchase" name="purchase" placeholder="Purchase" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">
        </div>
        <div class="form-group">
            <label for="profit"><?php echo trans('label_profit_(%)'); ?> <i class="text-danger">*</i></label>
            <input type="text" class="form-control typeable " id="profit" name="profit" placeholder="Profit (%)" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">

        </div>
        <div class="form-group d-none">
            <label for="max-dis"><?php echo trans('label_max_discount_(%)'); ?> <i class="text-danger">*</i></label>
            <input type="text" class="form-control typeable " id="max-dis" name="max_dis" placeholder="maximum_discount_(%)" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="1" required="">
        </div>
        <div class="form-group">
            <label for="any-note"><?php echo trans('label_any_note'); ?> <i class="text-danger">*</i></label>
            <textarea
                class="form-control typeable"
                id="any-note"
                name="any_note"
                placeholder="Enter your note here..."
                rows="3"
                required
                onclick="return select()"></textarea>
        </div>
    </div>
    <div id="page-2">
        <div class="form-group">
            <label for="cwgt"><?php echo trans('label_re-enter_weight_(g)'); ?> <i class="text-danger">*</i></label>
            <input type="text" class="form-control typeable " id="cwgt" name="cwgt" placeholder="Re-enter weight (g)" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">
        </div>
        <div class="form-group">
            <label for="size"><?php echo trans('label_size'); ?> <i class="text-danger">*</i></label>
            <input type="text" class="form-control typeable " id="size" name="size" placeholder="size" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" onkeyup="if(this.value<0){this.value='1';}" onclick="return select()" value="0.00" required="">
        </div>
    </div>

    <!-- <div class="form-group">
            <label for="sts"><?php // echo trans("label_status") 
                                ?><i class="text-danger">*</i></label>
            <select name="sts" id="sts" class="form-control typeable ">
                <option value="0"><?php // echo trans("label_for_sale") 
                                    ?></option>
                <option value="1"><?php // echo trans("label_not_for_sale") 
                                    ?></option>
            </select>
        </div> -->

    <div class="row mt-3">
        <div class="col-lg-6 mx-auto text-center">
            <button type="button" class="btn btn-outline-success" id="product_add">
                <?php echo trans('button_next'); ?> <i class="fas fa-arrow-right"></i>
            </button>
            <button type="button" class="btn btn-outline-success" id="create_product_submit">
                <i class="fas fa-save"></i> <?php echo trans('button_create'); ?>
            </button>
            <button type="reset" id="create_product_reset" class="btn btn-outline-danger ml-3">
                <i class="fas fa-undo"></i> <?php echo trans('label_reset_(esc)'); ?>
            </button>
        </div>
    </div>
</form>