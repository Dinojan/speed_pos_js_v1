<?php
function get_selected_category_products($category_id)
{
    $product_model = registry()->get('loader')->model('product');
    $products = $product_model->getProductsCategoryWise($category_id);
    return $products;
}
