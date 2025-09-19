<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$payment_model = registry()->get('loader')->model('payment'); // call modal file


function validate_request_data($request)
{
    if (!validateString($request->post['order_id'])) {
        throw new Exception(trans('error_order_id'));
    }

    if (!validateString($request->post['cus_id'])) {
        throw new Exception(trans('error_cus_id'));
    }

    if (empty($request->post['order_no'])) {
        throw new Exception(trans('error_order_no'));
    }

    if (empty($request->post['paid_amount']) || $request->post['paid_amount'] == 0) {
        throw new Exception(trans('error_paid_amount'));
    }
}

// CREATE payment
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        // if (user_group_id() != 1 && !has_permission('access', 'create_order')) {
        //     throw new Exception(trans('error_create_permission'));
        // }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        // validate_existance($request);
        // $ref = new_ref_no();
        // check customer
        // if (!isset($request->post['c_id']) && $request->post['c_id'] != null) {
        //     $statement = db()->prepare("INSERT INTO `customer` ( `c_name`, `c_mobile`, `c_address`) VALUES (?, ?, ?)");
        //     $statement->execute(array($data['cus_name'], $data['cus_mobile'], $data['cus_address']));
        //     $cid = db()->lastInsertId();
        // } else {
        //     $cid = $request->post['c_id'];
        // }
        $payment_id = $payment_model->addPayment($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $payment_id));
        exit();
    } catch (Exception $e) {

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}
