<?php 
function get_all_customers($sts){
    $modal = registry()->get('loader')->model('customer');
    return $modal->getcustomers($sts);
}

function get_the_customer($id){
    $customer_model = registry()->get('loader')->model('customer');
    return $customer_model->getcustomer($id);
}