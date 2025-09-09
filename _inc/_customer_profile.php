<?php
ob_start();
include("../_init.php");

// Check, if user logged in or not
// If user is not logged in then return an alert message
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}

// Check, if user has reading permission or not
// If user have not reading permission return an alert message
if (user_group_id() != 1 && !has_permission('access', 'read_customer')) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_read_permission')));
    exit();
}
$customer_model = registry()->get('loader')->model('customer');


if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_TABLE_DATA") {
    try {
        $data = array();
        $where_query = ' WHERE  1=1';
        if (isset($request->get['cId']) && $request->get['cId'] != null) {
            $where_query .= ' AND c_id = ' . $request->get['cId'];
        }

        if (isset($request->get['loan_id']) && $request->get['loan_id'] != null) {
            $where_query .= ' AND loan_id = ' . $request->get['loan_id'];
        }
        // if(user_group_id() != 1){
        //     $where_query .= " AND customer.`id` != 1 ";
        // } 
        // if(user_group_id() == 2) {
        //     $where_query .= " AND user_to_store.`store_id` = ".store_id();
        // }

        $statement = db()->prepare("SELECT * FROM payments $where_query");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;

        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;

            $dateTime = date("d F, Y / h:i A", strtotime($row['created_at']));
            $row['created_at'] = $dateTime;

            $row['agent'] =  get_the_user($row['created_by'], 'username');

            if ($row['ref_no']) {
                $row['ref_no'] = $row['ref_no'];
            } else {
                $row['ref_no'] = 'Reference number not added';
            }

            $payment_type = ucfirst($row['payment_type']);
            $row['payment_type'] = $payment_type;

            $amount = $row['pay_amount'];
            $row["amount"] = number_format($amount, 2);

            $row['view'] = '<a href="user_profile.php?u=' . $row['id'] . '" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-user"></i></a>';
        }

        echo json_encode(['success' => true, 'data' => $data]);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}
