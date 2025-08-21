<?php
ob_start();
include("../_init.php");
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
if (user_group_id() != 1 && !has_permission('access', 'read_user')) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_read_permission')));
    exit();
}
$user_model = registry()->get('loader')->model('user');
function validate_request_data($request)
{
    if (!validateString($request->post['name'])) {
        throw new Exception(trans('error_user_name'));
    }

    if (!validateEmail($request->post['email'])) {
        throw new Exception(trans('error_user_email'));
    }
    if (empty($request->post['phone'])) {
        throw new Exception(trans('error_user_mobile'));
    }
    if (!validateInteger($request->post['group_id'])) {
        throw new Exception(trans('error_user_group_name'));
    }


    if (empty($request->post['store'])) {
        throw new Exception(trans('error_store'));
    }
}
// Check existance by id
function validate_existance($request, $id = 0)
{
    $statement = db()->prepare("SELECT * FROM `users` WHERE (`email` = ? OR  mobile = ?) AND `id` != ?");
    $statement->execute(array($request->post['email'], $request->post['phone'], $id));
    if ($statement->rowCount() > 0) {
        throw new Exception(trans('error_mobile_or_email_exist'));
    }
}

if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_user')) {
            throw new Exception(trans('error_read_permission'));
        }
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request);

        // add role
        $user_id = $user_model->addUser($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_success'), 'id' => $user_id));
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
        // Check update permission
        if (user_group_id() != 1 && !has_permission('access', 'update_user')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_user_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request, $id);
        // Edit role
        $user_id = $user_model->editUser($id, $request->post);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $id));
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
        // Check delete permission

        if (user_group_id() != 1 && !has_permission('access', 'delete_user')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_user_id'));
        }

        $id = $request->post['id'];
        if ($id == 1) {
            throw new Exception(trans('error_delete_permission'));
        }

        $user_id = $user_model->deleteUser($id);
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


// create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') {
    include 'template/user_create_form.php';
    exit();
}

// edit form
if (isset($request->get['id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    $the_user = $user_model->getUser($request->get['id']);
    include 'template/user_edit_form.php';
    exit();
}
// delete form
if (isset($request->get['id']) && isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {
    $the_user = $user_model->getUser($request->get['id']);
    include 'template/user_delete_form.php';
    exit();
}
if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_TABLE_DATA") {
    try {
        $data = array();
        $statement = db()->prepare("SELECT * FROM users");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;
            $statement = db()->prepare("SELECT * FROM user_group WHERE group_id = '" . $row['group_id'] . "'");
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $role = $statement->fetch(PDO::FETCH_ASSOC);
                $row['user_group'] = $role['g_name'];
            } else {
                $row['user_group'] = '-';
            }


            
            // if ($row['role_id'] != 1) {
            $row['edit'] = '<button id="edit-user"  class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            // } else {
            // $row['view'] = '<button id="view-user" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-eye"></i></button>';
            $row['view'] = '<a href="user_profile.php?id=' . $row['id'] . '" class="btn btn-outline-info btn-sm" title="View">
                    <i class="fas fa-eye"></i>
                 </a>';
            // }
            if ($row['group_id'] != 1) {
                $row['delete'] = '<button id="delete-user" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
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