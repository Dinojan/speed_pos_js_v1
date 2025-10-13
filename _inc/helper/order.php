<?php 
function get_all_orders($sts){
    $modal = registry()->get('loader')->model('order');
    return $modal->getorders($sts);
}

function get_the_order($id){
    $order_model = registry()->get('loader')->model('order');
    return $order_model->getorder($id);
}
