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
            $stmt = $pdo->prepare("SELECT * FROM category WHERE p_id = ? ORDER BY c_name ASC");
            $stmt->execute([$parentId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $tree = [];
            $counter = 1;

            foreach ($rows as $row) {
                $rowNumber = $prefix === '' ? (string) $counter : $prefix . '.' . $counter;

                // 1️⃣ Get total weight of products in this category
                $stmtProd = $pdo->prepare("SELECT COALESCE(SUM(wgt * qty),0) as total_wgt, COALESCE(SUM(qty),0) as total_pcs 
                                           FROM product 
                                           WHERE c_id = ?");
                $stmtProd->execute([$row['id']]);
                $productData = $stmtProd->fetch(PDO::FETCH_ASSOC);

                $row['sl'] = $rowNumber; 
                $row['wgt'] = (float)$productData['total_wgt'];
                $row['pcs'] = (int)$productData['total_pcs'];
                $row['view'] = '';
                $row['edit'] = '<a href="#" class="btn btn-success btn-edit btn-sm"  data-id="'.$row['id'].'"><i class="fas fa-edit"></i> </a>';
                $row['delete'] = '<a href="#" class="btn btn-danger btn-del btn-sm" data-id="'.$row['id'].'"><i class="fas fa-trash"></i> </a>';

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
