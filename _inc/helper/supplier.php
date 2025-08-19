<?php 
function get_suppliers(){
    $supplier_model = registry()->get('loader')->model('supplier');
    return $supplier_model->getSuppliers();
}