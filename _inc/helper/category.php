<?php
function get_all_categories($sts)
{
    $modal = registry()->get('loader')->model('category');
    return $modal->getCategories($sts);
}

function get_category_tree()
{
    $modal = registry()->get('loader')->model('category');
    return $modal->categoryTree();
}

function get_the_category($id)
{
    $modal = registry()->get('loader')->model('category');
    return $modal->getCategory($id);
}

function set_category_tree_to_select($categories, $prefix = '', $selected = null, $for = 'edit')
{
    $html = '';
    foreach ($categories as $category) {
        if ($for == 'delete') {
            if ($category['id'] == $selected) continue;
        }
        $isSelected = ($selected == $category['id']) ? 'selected' : '';
        // Show sl + category name
        $html .= '<option value="' . $category['id'] . '" ' . $isSelected . '>'
            . $prefix . $category['sl'] . '. ' . $category['c_name']
            . '</option>';

        // If children exist, call recursively with more indentation
        if (!empty($category['children'])) {
            $html .= set_category_tree_to_select($category['children'], $prefix . '-- ', $selected, $for);
        }
    }
    return $html;
}
// function set_category_tree_to_sidebar($categories)
// {
//     $html = '<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">';

//     foreach ($categories as $category) {
//         $hasChildren = !empty($category['children']);

//         $html .= '<li class="nav-item">';

//         // parent link
//         $html .= '<a href="#" class="nav-link">';
//         $html .= '<i class="nav-icon fas fa-circle"></i>';
//         $html .= '<p>' . htmlspecialchars($category['c_name']);

//         if ($hasChildren) {
//             $html .= '<i class="right fas fa-angle-left"></i>';
//         }

//         $html .= '</p></a>';

//         // children recursive
//         if ($hasChildren) {
//             $html .= '<ul class="nav nav-treeview">';
//             $html .= set_category_tree_to_sidebar($category['children']);
//             $html .= '</ul>';
//         }

//         $html .= '</li>';
//     }

//     $html .= '</ul>';
//     return $html;
// }
function set_category_tree_to_sidebar($categories)
{
    $html = '';

    foreach ($categories as $category) {
        $hasChildren = !empty($category['children']);
        $border = $category['p_id'] == 0 ? ' style="border-bottom: #3882ed solid 1px !important; border-left: #3882ed solid 1px !important;"' : '';
        //$ng_click = $category['p_id'] == 0 ? ' style="border-bottom: #3882ed solid 1px !important; border-left: #3882ed solid 1px !important;"' : '';

        $html .= '<li class="nav-item pl-1 border-bottom border-left "' . $border . ' >';

        // parent link
        $html .= '<a href="#" class="nav-link category-select" data-cid="' . $category['id'] . '">';
        // $html .= '<i class="nav-icon fas fa-circle"></i>';
        $html .= '<p class="pl-0">' . htmlspecialchars($category['c_name']);

        if ($hasChildren) {
            $html .= '<i class="right fas fa-angle-left"></i>';
        }

        $html .= '</p></a>';

        // children recursive
        if ($hasChildren) {
            $html .= '<ul class="nav nav-treeview">';
            $html .= set_category_tree_to_sidebar($category['children']);
            $html .= '</ul>';
        }

        $html .= '</li>';
    }

    return $html;
}

// main wrapper
function render_sidebar($categories)
{
    $searchBox = '
        <div class="sidebar-search px-0 py-2">
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Search category..." id="sidebar-search-input">
            </div>
        </div>
    ';

    return $searchBox .
        '<ul class="nav nav-pills nav-sidebar flex-column pl-0" data-widget="treeview" role="menu" data-accordion="false">'
        . set_category_tree_to_sidebar($categories)
        . '</ul>';
}

function set_materials_to_select()
{
    // $html = '';
    $model = registry()->get('loader')->model('category');
    $materials = $model->get_materials();
    return $materials;
    // foreach ($materials as $material) {
    //     $html .= '<option value="' . $material['id'] . '">' . $material['sl'] . '. ' . $material['c_name'] . '</option>';
    // }
}
