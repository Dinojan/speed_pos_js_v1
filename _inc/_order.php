<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$order_model = registry()->get('loader')->model('order'); // call modal file

function validate_request_data($request)
{
    if (!validateString($request->post['cus_name'])) {
        throw new Exception(trans('error_order_name'));
    }

    if (!validateString($request->post['cus_mobile'])) {
        throw new Exception(trans('error_order_mobile'));
    }

    if (empty($request->post['cus_address'])) {
        throw new Exception(trans('error_order_address'));
    }

    if (empty($request->post['order_details'])) {
        throw new Exception(trans('error_order_details'));
    }

    if (empty($request->post['total_amt'])) {
        throw new Exception(trans('error_total_amt'));
    }

    if (empty($request->post['advance_amt'])) {
        throw new Exception(trans('error_advance_amt'));
    }
}
// Check existance by id
// function validate_existance($request, $id = 0)
// {
//     $statement = db()->prepare("SELECT * FROM `order` WHERE `p_code` = ? AND `id` != ?");
//     $statement->execute(array($request->post['p_code'], $id));
//     if ($statement->rowCount() > 0) {
//         throw new Exception(trans('error_order_code_exist'));
//     }
// }


function new_ref_no()
{
    $prfx = "OR";

    // Get the latest order id
    $statement = db()->prepare("SELECT id FROM orders ORDER BY id DESC LIMIT 1");
    $statement->execute();
    $lastEntry = $statement->fetch(PDO::FETCH_ASSOC);

    // If no records yet, start from 1001
    $lastId = $lastEntry ? (int) $lastEntry['id'] : 0;
    $newId = 1001 + $lastId;

    // Generate reference
    return $prfx . $newId; // e.g. OR1001, OR1002...
}

// CREATE order 
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_order')) {
            throw new Exception(trans('error_create_permission'));
        }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        // validate_existance($request);
        $ref = new_ref_no();
        // check customer
        if (!isset($request->post['c_id']) || $request->post['c_id'] == null) {
            $statement = db()->prepare("INSERT INTO `customer` ( `c_name`, `c_mobile`, `c_address`) VALUES (?, ?, ?)");
            $statement->execute(array($request->post['cus_name'], $request->post['cus_mobile'], $request->post['cus_address']));
            $cid = db()->lastInsertId();
        } else {
            $cid = $request->post['c_id'];
        }
        // add role
        $order_id = $order_model->addorder($ref, $cid, $request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $order_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'update_order')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_order_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        // validate_existance($request, $id);
        // Edit role
        $order_id = $order_model->editorder($id, $request->post);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $order_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'delete_order')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_order_id'));
        }
        $id = $request->post['id'];
        $checkorder = $order_model->getorder($id);
        if ($checkorder['order_status'] != 0 && $checkorder['order_status'] != 2) {
            throw new Exception(trans('error_can_not_delete_this_order'));
        }

        $new_status = 0;
        if ($checkorder['order_status'] == 2) {
            $new_status = 0;
        } else {
            $new_status = 2;
        }

        $statement = db()->prepare("UPDATE orders SET `order_status` = ? WHERE id = ?");
        $statement->execute([$new_status, $id]);

        if ($new_status == 0) {
            $ref = $checkorder['ref_no'];
            $paid_amount = $checkorder['total_paid'];
            if ($account_id = 1 && $paid_amount > 0) {
                $ref_no = unique_transaction_ref_no();

                $source_id = 1;
                $title = 'Deposit for order restore';
                $details = 'Customer name: ' . $checkorder['cus_name'];
                $image = 'NULL';
                $deposit_amount = $paid_amount;
                $transaction_type = 'deposit';

                $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $ref, $transaction_type, $title, $details, $image, user_id()));
                $info_id = db()->lastInsertId();

                $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
                $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

                $statement = db()->prepare("UPDATE `bank_account_to_store` SET `deposit` = `deposit` + {$deposit_amount} WHERE `store_id` = ? AND `account_id` = ?");
                $statement->execute(array(store_id(), $account_id));

                $statement = db()->prepare("UPDATE `bank_accounts` SET `total_deposit` = `total_deposit` + {$deposit_amount} WHERE `id` = ?");
                $statement->execute(array($account_id));
            }
        } else if ($new_status == 2) {
            $ref = $checkorder['ref_no'];
            $paid_amount = $checkorder['total_paid'];
            if ($account_id = 1 && $paid_amount > 0) {
                $ref_no = unique_transaction_ref_no();

                $source_id = 1;
                $title = 'Withdral for order delete';
                $details = 'Customer name: ' . $checkorder['cus_name'];
                $image = 'NULL';
                $deposit_amount = $paid_amount;
                $transaction_type = 'withdraw';

                $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $ref, $transaction_type, $title, $details, $image, user_id()));
                $info_id = db()->lastInsertId();

                $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
                $statement->execute(array(store_id(), $info_id, $ref_no, $deposit_amount));

                $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + {$deposit_amount} WHERE `store_id` = ? AND `account_id` = ?");
                $statement->execute(array(store_id(), $account_id));

                $statement = db()->prepare("UPDATE `bank_accounts` SET `total_withdraw` = `total_withdraw` + {$deposit_amount} WHERE `id` = ?");
                $statement->execute(array($account_id));
            }
        }

        // $order_model->updateorder($id, $request->post);
        // $order = $order_model->deleteorder($id);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans($new_status == 2 ? "text_delete_success!" : "text_restore_success!"), 'id' => $id));
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
    include 'template/order_create_form.php';
    exit();
}
//edit form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_order_id'));
        }
        $id = $request->get['id'];
        $order = $order_model->getorder($id);
        if (!$order) {
            throw new Exception(trans('error_order_not_found'));
        }
        include 'template/order_edit_form.php';
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

        $from = from();
        $to = to();
        $where .= date_range_filter($from, $to);



        if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
            $where .= " AND order_status = 2";
        } else {
            $where .= " AND order_status != 2";
        }

        $statement = db()->prepare("SELECT * FROM orders $where");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;

            $totalDue = $row["total_amt"] - $row["total_paid"];
            $row["due"] = number_format($totalDue, 2);
            // $row['category'] = get_the_category($row['c_id'])['c_name'];
            // $row['supplier'] = get_the_supplier($row['s_id'])['s_name']." (". get_the_supplier($row['s_id'])['s_mobile'].")";

            $row["biller"] = get_the_user($row["created_by"], 'username');

            $row['customer'] = $row['cus_name'] . " (" . format_mobile($row['cus_mobile']) . ")";

            if ($totalDue > 0) {
                $row['pay'] = '<button id="pay-order" class="btn btn-outline-primary btn-sm pay-btn"  title="View"><i class="fas fa-money-bill"></i></button>';
            } else {
                $row['pay'] = '<button  class="btn btn-outline-secondary btn-sm pay-btn"  title="View"><i class="fas fa-money-bill"></i></button>';
            }
            // $row['view'] = '<button id="view-order" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-eye"></i></button>';
            $row['view'] = '<a href="customer_profile.php?customer=' . $row['cus_id'] . '&order=' . $row['id'] . '" class="btn btn-outline-info btn-sm" title="View">
                    <i class="fas fa-eye"></i>
                 </a>';
            //if ($row['id'] != 1) {
            $row['edit'] = '<button id="edit-order" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            // } else {
            //     $row['edit'] = '<button id="edit-order" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            // }
            if ($row['order_status'] == 2) {
                $row['delete'] = '<button id="delete-order" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-undo"></i></button>';
            } else if ($row['order_status'] == 0) {
                $row['delete'] = '<button id="delete-order" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
            } else {
                $row['delete'] = '<button id="delete-order" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete" disabled><i class="fas fa-trash-alt"></i></button>';
            }

            //  }
            //     $row['delete'] = '<button class="btn btn-outline-danger btn-sm delete-btn" disabled title="Delete"><i class="fas fa-trash-alt"></i></button>';
            // }
            //    if($row['status'] == 0){
            //     $row['sts'] = 'Active';

            // } else  if($row['status'] == 2){
            //     $row['sts'] = 'Deleted';

            // }else {
            //      $row['sts'] = 'inActive';
            // }

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

if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_DUE_ORDERS") {
    try {
        $data = array();
        $where = "WHERE 1=1 AND total_amt > total_paid";

        $from = from();
        $to = to();
        $where .= date_range_filter($from, $to);



        // if(isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2){
        //     $where .= " AND status = 2";
        // }else {
        //      $where .= " AND status != 2";
        // }

        $statement = db()->prepare("SELECT * FROM orders $where");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;

            $totalDue = $row["total_amt"] - $row["total_paid"];
            $row["due"] = number_format($totalDue, 2);
            // $row['category'] = get_the_category($row['c_id'])['c_name'];
            // $row['supplier'] = get_the_supplier($row['s_id'])['s_name']." (". get_the_supplier($row['s_id'])['s_mobile'].")";

            $row["biller"] = get_the_user($row["created_by"], 'username');

            $row['customer'] = $row['cus_name'] . " (" . format_mobile($row['cus_mobile']) . ")";

            if ($totalDue > 0) {
                $row['pay'] = '<button id="pay-order" class="btn btn-outline-primary btn-sm pay-btn"  title="View"><i class="fas fa-money-bill"></i></button>';
            } else {
                $row['pay'] = '<button  class="btn btn-outline-secondary btn-sm pay-btn"  title="View"><i class="fas fa-money-bill"></i></button>';
            }
            // $row['view'] = '<button id="view-order" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-eye"></i></button>';
            $row['view'] = '<a href="customer_profile.php?customer=' . $row['cus_id'] . '&order=' . $row['id'] . '" class="btn btn-outline-info btn-sm" title="View">
                    <i class="fas fa-eye"></i>
                 </a>';
            //if ($row['id'] != 1) {
            $row['edit'] = '<button id="edit-order" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            // } else {
            //     $row['edit'] = '<button id="edit-order" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            // }
            // if ($row['status'] == 2) {
            //  $row['delete'] = '<button id="delete-order" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-undo"></i></button>';
            //  } else {
            $row['delete'] = '<button id="delete-order" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';

            //  }
            //     $row['delete'] = '<button class="btn btn-outline-danger btn-sm delete-btn" disabled title="Delete"><i class="fas fa-trash-alt"></i></button>';
            // }
            //    if($row['status'] == 0){
            //     $row['sts'] = 'Active';

            // } else  if($row['status'] == 2){
            //     $row['sts'] = 'Deleted';

            // }else {
            //      $row['sts'] = 'inActive';
            // }

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
