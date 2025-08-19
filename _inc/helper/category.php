<?php 
function get_all_categories($sts){
    $modal = registry()->get('loader')->model('category');
    return $modal->getCategories($sts);
}