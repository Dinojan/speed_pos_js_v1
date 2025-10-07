<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$category_model = registry()->get('loader')->model('category');

function validate_request_data($request)
{
    if (empty($request->post['c_name'])) {
        throw new Exception(trans('error_category_name'));
    }
}
// Check existance by id
function validate_existance($request, $id = 0)
{
    $statement = db()->prepare("SELECT * FROM `category` WHERE `c_name` = ?  AND  `id` != ?");
    $statement->execute(array($request->post['c_name'], $id));
    if ($statement->rowCount() > 0) {
        throw new Exception(trans('error_category_name_exist'));
    }
}
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_category')) {
            throw new Exception(trans('error_create_permission'));
        }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request);

        // add role
        $c_id = $category_model->addCategory($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $c_id));
        exit();
    } catch (Exception $e) {

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE') {
    try {
        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'update_category')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_category_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request, $id);


        $c_id = $category_model->editCategory($id, $request->post);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $c_id));
        exit();
    } catch (Exception $e) {

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') {
    try {
        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'delete_category')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_category_id'));
        }

        $id = $request->post['id'];
        if ($id == 1) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['delete_action'])) {
            throw new Exception(trans('error_delete_action'));
        }
        if ($request->post['delete_action'] == 'insert_to' && empty($request->post['new_c_id'])) {
            throw new Exception(trans('error_new_category_name'));
        }
        if ($request->post['delete_action'] == 'insert_to') {
            $statement = db()->prepare("UPDATE `category` SET `p_id` = ? WHERE `p_id` = ?");
            $statement->execute(array($request->post['new_c_id'], $id));

            $statement = db()->prepare("UPDATE `product` SET `c_id` = ? WHERE `c_id` = ?");
            $statement->execute(array($request->post['new_c_id'], $id));
        }
        $user_group = $category_model->deleteCategory($id);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_delete_success'), 'id' => $id));
        exit();
    } catch (Exception $e) {

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') {
    include 'template/category_create_form.php';
    exit();
}

if (isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_category_id'));
        }
        $id = $request->get['id'];
        $category = $category_model->getCategory($id);
        if (!$category) {
            throw new Exception(trans('error_category_not_found'));
        }
        include 'template/category_edit_form.php';
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

if (isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_category_id'));
        }
        $id = $request->get['id'];
        $category = $category_model->getCategory($id);
        if (!$category) {
            throw new Exception(trans('error_category_not_found'));
        }
        include 'template/delete_category_form.php';
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_TABLE_DATA") {
    try {
        // Recursive function to build tree with weights
        function getCategoryTree($parentId = 0, $prefix = '')
        {
            $pdo = db();

            // Get categories under this parent
            $stmt = $pdo->prepare("SELECT * FROM category WHERE p_id = ?  ORDER BY c_name ASC");
            $stmt->execute([$parentId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $tree = [];
            $counter = 1;

            foreach ($rows as $row) {
                $rowNumber = $prefix === '' ? (string) $counter : $prefix . '.' . $counter;

                // 1️⃣ Get total weight of products in this category
                $stmtProd = $pdo->prepare("SELECT COALESCE(SUM(wgt * qty),0) as total_wgt, COALESCE(SUM(qty),0) as total_pcs 
                                           FROM product 
                                           WHERE c_id = ? AND status != 2");
                $stmtProd->execute([$row['id']]);
                $productData = $stmtProd->fetch(PDO::FETCH_ASSOC);

                $row['sl'] = $rowNumber;
                $row['wgt'] = (float)$productData['total_wgt'];
                $row['pcs'] = (int)$productData['total_pcs'];
                $row['view'] = '';
                $row['edit'] = '<a href="#" class="btn btn-success btn-edit btn-sm"  data-id="' . $row['id'] . '"><i class="fas fa-edit"></i> </a>';
                $row['delete'] = '<a href="#" class="btn btn-danger btn-del btn-sm" data-id="' . $row['id'] . '"><i class="fas fa-trash"></i> </a>';

                // 2️⃣ Recursively get children categories
                $children = getCategoryTree($row['id'], $rowNumber);

                // 3️⃣ If children exist, add their weights to parent
                if (!empty($children)) {
                    foreach ($children as $child) {
                        $row['wgt'] += $child['wgt'];
                        $row['pcs'] += $child['pcs'];
                    }
                    $row['children'] = $children;
                }

                $tree[] = $row;
                $counter++;
            }

            return $tree;
        }

        $treeData = getCategoryTree(0);

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(["data" => $treeData], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errorMsg' => $e->getMessage()]);
        exit();
    }
}


if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_CAT_DATA") {
    try {
        // Recursive function to build tree with weights
        function getCategoryTree($parentId = 0, $prefix = '')
        {
            $pdo = db();

            // Get categories under this parent
            $stmt = $pdo->prepare("SELECT * FROM category WHERE p_id = ?  ORDER BY c_name ASC");
            $stmt->execute([$parentId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $tree = [];
            $counter = 1;

            foreach ($rows as $row) {
                $rowNumber = $prefix === '' ? (string) $counter : $prefix . '.' . $counter;

                // 1️⃣ Get total weight of products in this category
                $stmtProd = $pdo->prepare("SELECT COALESCE(SUM(wgt * qty),0) as total_wgt, COALESCE(SUM(qty),0) as total_pcs FROM product WHERE c_id = ? AND status = 0");
                $stmtProd->execute([$row['id']]);
                $productData = $stmtProd->fetch(PDO::FETCH_ASSOC);

                $children = getCategoryTree($row['id'], $rowNumber);
                $row['children'] = $children;



                $row['sl'] = $rowNumber;
                $row['wgt'] = (float)$productData['total_wgt'];
                $row['pcs'] = (int)$productData['total_pcs'];
                // Recursively get children
                $children = getCategoryTree($row['id'], $rowNumber);
                $fullPath = getCategoryPath($row['id']);
                $material = getMaterial($row['id']);

                // Encode full path as JSON string for JS
                $fullPathJs = json_encode($fullPath);


                $row['add_new_item'] = '<button type="button" class="btn btn-sm font-weight-bold text-primary" ng-click="openAddCategoryModal(' . htmlspecialchars(json_encode($row)) . ')"> <i class="fas fa-plus"></i> ' . trans("button_add_category") . '</button>';
                // Only leaf categories get Add Product button
                if (empty($children)) {
                    $row['add_new_cat'] = '<button type="button" class="btn btn-sm font-weight-bold text-primary" ng-click=\'openAddProductModal('
                        . htmlspecialchars(json_encode($row)) . ', '
                        . $fullPathJs . ', '
                        . $material . ')\'>'
                        . '<i class="fas fa-plus"></i> ' . trans("button_add_product") . '</button>';
                } else {
                    $row['add_new_cat'] = '';
                    $row['children'] = $children;
                }

                // Fetch products
                $stmtP = $pdo->prepare("SELECT * FROM product WHERE c_id = ? AND status = ?");
                $stmtP->execute([$row['id'], 0]);
                $products = $stmtP->fetchAll(PDO::FETCH_ASSOC);
                $p_index = 0;
                foreach ($products as &$prod) {
                    $p_index++;
                    $prod['row_index'] = $p_index;

                    $prod['category'] = get_the_category($prod['c_id'])['c_name'];
                    $prod['supplier'] = get_the_supplier($prod['s_id'])['s_name'] . " (" . format_mobile(get_the_supplier($prod['s_id'])['s_mobile']) . ")";

                    $prod['view'] = '<button class="btn btn-outline-info btn-sm view-btn" title="View" data-id="' . $prod['id'] . '"><i class="fas fa-eye"></i></button>';

                    if ($prod['status'] == 2 || $prod['status'] == 3) {
                        $prod['edit'] = '<button class="btn btn-outline-success btn-sm edit-btn" title="Edit" disabled><i class="fas fa-edit"></i></button>';
                    } else {
                        $prod['edit'] = '<button class="btn btn-outline-success btn-sm edit-btn" title="Edit"><i class="fas fa-edit"></i></button>';
                    }

                    if ($prod['status'] == 3) {
                        $prod['delete'] = '<button id="delete-product" class="btn btn-sm delete-btn delete-product" title="Delete"  data-id="'. $prod['id'] .'" disabled><i class="fas fa-trash-alt"></i></button>';
                    } else if ($prod['status'] == 2) {
                        $prod['delete'] = '<button id="delete-product" class="btn btn-sm delete-btn text-anger delete-product disabled"  data-id="'. $prod['id'] .'" title="Delete"><i class="fas fa-undo"></i></button>';
                    } else {
                        $prod['delete'] = '<button id="delete-product" class="btn btn-sm delete-btn text-danger delete-product"  data-id="'. $prod['id'] .'" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                    }

                    // Status badge
                    if ($prod['status'] == 0) $prod['sts'] = '<span class="badge bg-success p-1">For sale</span>';
                    else if ($prod['status'] == 2) $prod['sts'] = '<span class="badge bg-danger p-1">Deleted</span>';
                    else if ($prod['status'] == 3) $prod['sts'] = '<span class="badge bg-secondary p-1">Sold</span>';
                    else $prod['sts'] = '<span class="badge bg-warning p-1">Not for sale</span>';
                }

                $row['products'] = $products;

                $row['edit'] = '<a href="#" class="btn btn-edit btn-sm font-weight-bold text-success" ng-click="cat_edit('.$row['id'].')"   data-id="' . $row['id'] . '"><i class="fas fa-edit"></i> </a>';
                // Check if this category has children or products
                $hasChildren = !empty($children);
                $hasProducts = !empty($products);

                if ($hasChildren || $hasProducts) {
                    $row['delete'] = '<a href="javascript:void(0)" class="btn cat-del btn-sm font-weight-bold text-danger disabled" data-id="' . $row['id'] . '" onclick="javascript:void(0)"><i class="fas fa-trash"></i> </a>';
                } else {
                    $row['delete'] = '<a href="javascript:void(0)" ng-click="cat_del('.$row['id'].')" class="btn cat-del btn-sm font-weight-bold text-danger" data-id="' . $row['id'] . '"><i class="fas fa-trash"></i> </a>';
                }

                // 2️⃣ Recursively get children categories
                $children = getCategoryTree($row['id'], $rowNumber);

                // 3️⃣ If children exist, add their weights to parent
                if (!empty($children)) {
                    foreach ($children as $child) {
                        $row['wgt'] += $child['wgt'];
                        $row['pcs'] += $child['pcs'];
                    }
                    $row['children'] = $children;
                }

                $tree[] = $row;
                $counter++;
            }

            return $tree;
        }

        $treeData = getCategoryTree(0);

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(["data" => $treeData], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errorMsg' => $e->getMessage()]);
        exit();
    }
}


// if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_TABLE_DATA") {
//     try {
//         // Recursive function to build tree
//         function getCategoryTree($parentId = 0, $prefix = '')
//         {
//             $stmt = db()->prepare("SELECT * FROM category WHERE p_id = ? ORDER BY c_name ASC");
//             $stmt->execute([$parentId]);
//             $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

//             $tree = [];
//             $counter = 1; // counter for children at this level

//             foreach ($rows as $row) {
//                 // Generate hierarchical number
//                 $rowNumber = $prefix === '' ? (string) $counter : $prefix . '.' . $counter;
//                 $row['sl'] = $rowNumber; 
//                 $row['wgt'] = 0;
//                 $row['pcs'] = 0;
//                 $row['view'] = '';//<a href="#" class="btn btn-primary  btn-view  btn-sm" data-id="'.$row['id'].'"><i class="fas fa-eye"></i> </a>';
//                 $row['edit'] = '<a href="#" class="btn btn-success btn-edit btn-sm"  data-id="'.$row['id'].'"><i class="fas fa-edit"></i> </a>';
//                 $row['delete'] = '<a href="#" class="btn btn-danger btn-del btn-sm" data-id="'.$row['id'].'"><i class="fas fa-trash"></i> </a>';

//                 // Recursively get children, passing the new prefix
//                 $children = getCategoryTree($row['id'], $rowNumber);
//                 if (!empty($children)) {
//                     $row['children'] = $children;
//                 }

//                 $tree[] = $row;
//                 $counter++;
//             }

//             return $tree;
//         }


//         $treeData = getCategoryTree(0); // root categories (p_id = 0)

//         header('Content-Type: application/json; charset=UTF-8');
//         echo json_encode(["data" => $treeData], JSON_PRETTY_PRINT);

//     } catch (Exception $e) {
//         header('HTTP/1.1 422 Unprocessable Entity');
//         header('Content-Type: application/json; charset=UTF-8');
//         echo json_encode(['errorMsg' => $e->getMessage()]);
//         exit();
//     }
// }
