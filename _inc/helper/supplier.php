<?php 
function get_suppliers(){
    $supplier_model = registry()->get('loader')->model('supplier');
    return $supplier_model->getSuppliers();
}

function get_the_supplier($id){
    $supplier_model = registry()->get('loader')->model('supplier');
    return $supplier_model->getSupplier($id);
}