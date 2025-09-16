<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$customer_model = registry()->get('loader')->model('customer'); // call modal file

function validate_request_data($request)
{
    if (!validateString($request->post['c_name'])) {
        throw new Exception(trans('error_customer_name'));
    }
    if (!validateString($request->post['c_mobile'])) {
        throw new Exception(trans('error_customer_mobile'));
    }
    if (!validateString($request->post['c_address'])) {
        throw new Exception(trans('error_customer_address'));
    }
}
// Check existance by id
function validate_existance($request, $id = 0)
{
    $statement = db()->prepare("SELECT * FROM `customer` WHERE `c_mobile` = ? AND `id` != ?");
    $statement->execute(array($request->post['c_mobile'], $id));
    if ($statement->rowCount() > 0) {
        throw new Exception(trans('error_customer_mobile_exist'));
    }
}
// CREATE customer 
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_customer')) {
            throw new Exception(trans('error_create_permission'));
        }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request);

        // add role
        $customer_id = $customer_model->addcustomer($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $customer_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'update_customer')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_customer_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request, $id);
        // Edit role
        $customer_id = $customer_model->editCustomer($id, $request->post);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $customer_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'delete_customer')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_customer_id'));
        }
        $id = $request->post['id'];
        $checkcustomer = $customer_model->getcustomer($id);
        if ($checkcustomer['status'] == 2) {
            $sts = 1;

            $msg = 'text_restore_success';
        } else {
            $sts = 2;
            $msg = 'text_move_to_bin_success';
        }

        $customer = $customer_model->updatecustomerStatus($id, $sts);
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
    include 'template/customer_create_form.php';
    exit();
}
//edit form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_customer_id'));
        }
        $id = $request->get['id'];
        $customer = $customer_model->getcustomer($id);
        if (!$customer) {
            throw new Exception(trans('error_customer_not_found'));
        }
        include 'template/customer_edit_form.php';
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_THE_CUSTOMER" && $request->get['c_id']) {
    try {
        if (empty($request->get['c_id'])) {
            throw new Exception(trans('error_customer_id'));
        }
        $id = $request->get['c_id'];
        $customer = $customer_model->getcustomer($id);
        if (!$customer) {
            throw new Exception(trans('error_customer_not_found'));
        }
        echo json_encode(array("customer" => $customer));
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

        if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
            $where .= " AND status = 2";
        } else {
            $where .= " AND status != 2";
        }

        $statement = db()->prepare("SELECT * FROM customer $where");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;
            $row['pay'] = '<button id="view-customer" class="btn btn-outline-success btn-sm view-btn"  title="View"><i class="fas fa-money-bill-wave"></i></button>';
            // $row['profile'] = '<button id="view-customer" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-user"></i></button>';
            $row['profile'] = '<a href="customer_profile.php?id=' . $row['id'] . '" 
                      class="btn btn-outline-info btn-sm view-btn"
                      title="View ">
                      <i class="fas fa-user"></i>
                   </a>';


            //if ($row['id'] != 1) {
            $row['edit'] = '<button id="edit-customer" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            // } else {
            //     $row['edit'] = '<button id="edit-customer" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            // }
            if ($row['status'] == 2) {
                $row['delete'] = '<button id="delete-customer" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-undo"></i></button>';
            } else {
                $row['delete'] = '<button id="delete-customer" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
            }
            //     $row['delete'] = '<button class="btn btn-outline-danger btn-sm delete-btn" disabled title="Delete"><i class="fas fa-trash-alt"></i></button>';
            // }
            if ($row['status'] == 0) {
                $row['sts'] = 'Active';
            } else if ($row['status'] == 2) {
                $row['sts'] = 'Deleted';
            } else {
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

// Get default customer
if (isset($_GET['action_type']) && $_GET['action_type'] == 'DEFAULT_CUSTOMER' && isset($_GET['cus']) && $_GET['cus'] == "last") {

    try {
        $statement = db()->prepare("SELECT * FROM customer  ORDER BY id DESC LIMIT 1");
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        // ✅ Send JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'msg' => 'Success',
            'customer' => $data
        ]);
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errorMsg' => $e->getMessage()]);
        exit();
    }
}
