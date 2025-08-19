<?php 
function get_all_categories($sts){
    $modal = registry()->get('loader')->model('category');
    return $modal->getCategories($sts);
}

function get_category_tree(){
     $modal = registry()->get('loader')->model('category');
    return $modal->categoryTree();
}

function get_the_category($id){
     $modal = registry()->get('loader')->model('category');
    return $modal->getCategory($id);
}

function set_category_tree_to_select($categories, $prefix = '', $selected = null,$for = 'edit') {
    $html = '';
    foreach ($categories as $category) {
        if($for == 'delete'){
            if($category['id'] == $selected) continue;
        }
        $isSelected = ($selected == $category['id']) ? 'selected' : '';
        // Show sl + category name
        $html .= '<option value="' . $category['id'] . '" ' . $isSelected . '>' 
              . $prefix . $category['sl'] . '. ' . $category['c_name'] 
              . '</option>';

        // If children exist, call recursively with more indentation
        if (!empty($category['children'])) {
            $html .= set_category_tree_to_select($category['children'], $prefix . '-- ', $selected,$for);
        }
    }
    return $html;
}
