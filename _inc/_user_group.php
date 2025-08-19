<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$usergroup_model = registry()->get('loader')->model('usergroup');

function validate_request_data($request)
{
    if (!validateString($request->post['group_name'])) {
        throw new Exception(trans('error_group_name'));
    }
}
// Check existance by id
function validate_existance($request, $id = 0)
{
    $statement = db()->prepare("SELECT * FROM `user_group` WHERE `g_name` = ?  AND `group_id` != ?");
    $statement->execute(array($request->post['group_name'], $id));
    if ($statement->rowCount() > 0) {
        throw new Exception(trans('error_name_exist'));
    }
}
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_user_group')) {
            throw new Exception(trans('error_create_permission'));
        }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request);

        // add role
        $user_group_id = $usergroup_model->adduserGroup($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $user_group_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'update_user_group')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_user_group_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request, $id);

        $permission = array();
        if (isset($request->post['access']) && $request->post['access']) {
            $permission['access'] = $request->post['access'];
        }
        if (isset($request->post['modify']) && $request->post['modify']) {
            $permission['modify'] = $request->post['modify'];
        }

        $permission = array();
        if (isset($request->post['access']) && $request->post['access']) {
            $permission['access'] = $request->post['access'];
        }
        if (isset($request->post['modify']) && $request->post['modify']) {
            $permission['modify'] = $request->post['modify'];
        }
        // Edit role
        $user_group_id = $usergroup_model->edituserGroup($id, $request->post, $permission);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $user_group_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'delete_user_group')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_user_group_id'));
        }

        $id = $request->post['id'];
        if ($id == 1) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['delete_action'])) {
            throw new Exception(trans('error_delete_action'));
        }
        if ($request->post['delete_action'] == 'insert_to' && empty($request->post['new_group_id'])) {
            throw new Exception(trans('error_new_user_group_name'));
        }
        if ($request->post['delete_action'] == 'insert_to') {
            $statement = db()->prepare("UPDATE `users` SET `group_id` = ? WHERE `group_id` = ?");
            $statement->execute(array($request->post['new_group_id'], $id));
        }
        $user_group = $usergroup_model->deleteuserGroup($id);
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
if (isset($request->get['action_type']) && $request->get['action_type'] == 'ADD_USER_GROUP') {
    include 'template/user_group_create_form.php';
    exit();
}

if (isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT_USER_GROUP') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_user_group_id'));
        }
        $id = $request->get['id'];
        $user_group = $usergroup_model->getuserGroup($id);
        if (!$user_group) {
            throw new Exception(trans('error_user_group_not_found'));
        }
        include 'template/user_group_edit_form.php';
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }

}

if (isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE_USER_GROUP') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_user_group_id'));
        }
        $id = $request->get['id'];
        $user_group = $usergroup_model->getuserGroup($id);
        if (!$user_group) {
            throw new Exception(trans('error_user_group_not_found'));
        }
        include 'template/user_group_delete_form.php';
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
        $statement = db()->prepare("SELECT * FROM user_group");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;
            $row['user_count'] = $usergroup_model->totalUser($row['group_id']);
            if ($row['group_id'] != 1) {
                $row['edit'] = '<button id="edit-user-group" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            } else {
                $row['edit'] = '<button id="edit-user-group" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            }
            if ($row['group_id'] != 1) {
                $row['delete'] = '<button id="delete-user-group" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
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