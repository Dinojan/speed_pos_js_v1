<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$supplier_model = registry()->get('loader')->model('supplier');

function validate_request_data($request)
{
    if (!validateString($request->post['s_name'])) {
        throw new Exception(trans('error_supplier_name'));
    }

     if (!validateString($request->post['s_mobile'])) {
        throw new Exception(trans('error_supplier_mobile'));
    }
}
// Check existance by id
function validate_existance($request, $id = 0)
{
    $statement = db()->prepare("SELECT * FROM `supplier` WHERE `s_mobile` = ? AND `id` != ?");
    $statement->execute(array($request->post['s_mobile'], $id));
    if ($statement->rowCount() > 0) {
        throw new Exception(trans('error_mobile_exist'));
    }
}
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_supplier')) {
            throw new Exception(trans('error_create_permission'));
        }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request);

        // add role
        $supplier_id = $supplier_model->addSupplier($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $supplier_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'update_supplier')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_supplier_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request, $id);
        // Edit role
        $supplier_id = $supplier_model->editSupplier($id, $request->post);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $supplier_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'delete_supplier')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_supplier_id'));
        }

        $id = $request->post['id'];
        if ($id == 1) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['delete_action'])) {
            throw new Exception(trans('error_delete_action'));
        }
        if ($request->post['delete_action'] == 'insert_to' && empty($request->post['new_group_id'])) {
            throw new Exception(trans('error_new_supplier_name'));
        }
        // if ($request->post['delete_action'] == 'insert_to') {
        //     $statement = db()->prepare("UPDATE `users` SET `group_id` = ? WHERE `group_id` = ?");
        //     $statement->execute(array($request->post['new_group_id'], $id));
        // }
        $supplier = $supplier_model->deleteSupplier($id);
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
    include 'template/supplier_create_form.php';
    exit();
}

if (isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_supplier_id'));
        }
        $id = $request->get['id'];
        $supplier = $supplier_model->getSupplier($id);
        if (!$supplier) {
            throw new Exception(trans('error_supplier_not_found'));
        }
        include 'template/supplier_edit_form.php';
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
            throw new Exception(trans('error_supplier_id'));
        }
        $id = $request->get['id'];
        $supplier = $supplier_model->getSupplier($id);
        if (!$supplier) {
            throw new Exception(trans('error_supplier_not_found'));
        }
        include 'template/supplier_delete_form.php';
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
        $statement = db()->prepare("SELECT * FROM supplier");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;
            $row['view'] = '<button id="view-supplier" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-eye"></i></button>';
            //if ($row['id'] != 1) {
                $row['edit'] = '<button id="edit-supplier" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            // } else {
            //     $row['edit'] = '<button id="edit-supplier" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            // }
            if ($row['id'] != 1) {
                $row['delete'] = '<button id="delete-supplier" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
            } else {
                $row['delete'] = '<button class="btn btn-outline-danger btn-sm delete-btn" disabled title="Delete"><i class="fas fa-trash-alt"></i></button>';
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