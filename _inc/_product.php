<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$product_model = registry()->get('loader')->model('product'); // call modal file

function validate_request_data($request)
{
    if (!validateString($request->post['p_name'])) {
        throw new Exception(trans('error_product_name'));
    }

    if (!validateString($request->post['p_code'])) {
        throw new Exception(trans('error_product_code'));
    }

    if (!validateInteger($request->post['c_id'])) {
        throw new Exception(trans('error_product_category'));
    }

    if (!validateInteger($request->post['s_id'])) {
        throw new Exception(trans('error_product_supplier'));
    }

    if (empty($request->post['wgt'])) {
        throw new Exception(trans('error_product_weight'));
    }
    if (empty($request->post['cost'])) {
        throw new Exception(trans('error_product_cost'));
    }
}
// Check existance by id
function validate_existance($request, $id = 0)
{
    $statement = db()->prepare("SELECT * FROM `product` WHERE `p_code` = ? AND `id` != ?");
    $statement->execute(array($request->post['p_code'], $id));
    if ($statement->rowCount() > 0) {
        throw new Exception(trans('error_product_code_exist'));
    }
}
// CREATE product 
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_product')) {
            throw new Exception(trans('error_create_permission'));
        }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request);

        // add role
        $product_id = $product_model->addproduct($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $product_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'update_product')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_product_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request, $id);
        // Edit role
        $product_id = $product_model->editproduct($id, $request->post);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $product_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'delete_product')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_product_id'));
        }
        $id = $request->post['id'];
        $checkProduct = $product_model->getproduct($id);
        if($checkProduct['status'] == 2){
            $sts = 1;
          
             $msg = 'text_restore_success';
        }else{
            $sts = 2; 
              $msg = 'text_move_to_bin_success';
        }

        $product = $product_model->updateProductStatus($id,$sts);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans($msg), 'id' => $id));
        exit();

    } catch (Exception $e) {

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}
// add form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') {
    include 'template/product_create_form.php';
    exit();
}
//edit form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_product_id'));
        }
        $id = $request->get['id'];
        $product = $product_model->getproduct($id);
        if (!$product) {
            throw new Exception(trans('error_product_not_found'));
        }
        include 'template/product_edit_form.php';
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
        $data = array();
        $where = "WHERE 1=1";

        if(isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2){
            $where .= " AND status = 2";
        }else {
             $where .= " AND status != 2";
        }

        $statement = db()->prepare("SELECT * FROM product $where");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;

            $row['category'] = get_the_category($row['c_id'])['c_name'];
            $row['supplier'] = get_the_supplier($row['s_id'])['s_name']." (". get_the_supplier($row['s_id'])['s_mobile'].")";

          
            
            $row['view'] = '<button id="view-product" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-eye"></i></button>';
            //if ($row['id'] != 1) {
            $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            // } else {
            //     $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            // }
            if ($row['status'] == 2) {
                $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-undo"></i></button>';
             } else {
                 $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
            
             }
            //     $row['delete'] = '<button class="btn btn-outline-danger btn-sm delete-btn" disabled title="Delete"><i class="fas fa-trash-alt"></i></button>';
            // }
               if($row['status'] == 0){
                $row['sts'] = 'Active';

            } else  if($row['status'] == 2){
                $row['sts'] = 'Deleted';

            }else {
                 $row['sts'] = 'inActive';
            }
            
        }
        // Return data as JSON
        echo json_encode(array("data" => $data));
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}