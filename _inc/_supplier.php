<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$supplier_model = registry()->get('loader')->model('supplier');

// stock checking history model
if (isset($request->get['action_type']) && $request->get['action_type'] == 'PAY_SUPPLIER_FORM') {
    try {
        $id = $request->get['supplier'];

        include "template/supplier_payment.php";
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}


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

        $suplier = $supplier_model->getSupplier($supplier_id);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'supplier' => $suplier));
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
        if ($request->post['delete_action'] == 'insert_to' && empty($request->post['new_s_id'])) {
            throw new Exception(trans('error_new_supplier_name'));
        }
        if ($request->post['delete_action'] == 'insert_to') {
            $statement = db()->prepare("UPDATE `product` SET `s_id` = ? WHERE `s_id` = ?");
            $statement->execute(array($request->post['new_s_id'], $id));
        }
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

            $costStmt = db()->prepare("SELECT SUM(cost) as total_cost, COUNT(*) as total_products FROM product WHERE s_id = ?");
            $costStmt->execute([$row['id']]);
            $productCost = $costStmt->fetch(PDO::FETCH_ASSOC);

            $row['total_cost'] = $productCost['total_cost'] ?? 0;
            $row['total_products'] = $productStats['total_products'] ?? 0;

            $total_paid = $row['total_paid'];
            if ($row['total_paid'] <  $productCost['total_cost']) {
                $row['total_paid'] =  "<div class='mx-3 row justify-content-between'><span>$total_paid</span>" . '<button id="pay-supplier"  data-id="' . $row['id'] . '" class="btn p-0"><i class="fas fa-plus text-success"></i></button></div>';
            } else {
                $row['total_paid'] =  $total_paid;
            }

            // $row['view'] = '<button id="view-supplier" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-eye"></i></button>';
            $row['view'] = '<a href="supplier_profile.php?supplier=' . $row['id'] . '" class="btn btn-outline-info btn-sm" title="View">
                    <i class="fas fa-eye"></i>
                 </a>';
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

if ($request->server['REQUEST_METHOD'] == 'POST' && $request->post['action_type'] == "SUPPLIER_PAYMENT") {
    try {
        $statement = db()->prepare("UPDATE supplier SET total_paid = total_paid + ? WHERE id = ?");
        $statement->execute([
            $request->post['payment'],
            $request->get['supplier']
        ]);

        $paid_amount = $request->post['payment'];
        if ($account_id = 1 && $paid_amount > 0) {
            $ref_no = unique_transaction_ref_no('withdraw');
            $ref = 'SUP00'. $request->get['supplier']; 

            $source_id = 1;
            $title = 'Withdraw for supplier payment';
            $details = 'Supplier name: ' . get_the_supplier($request->get['supplier'])['s_name'];
            $image = 'NULL';
            $withdraw_amount = $paid_amount;
            $transaction_type = 'deposit';

            $statement = db()->prepare("INSERT INTO `bank_transaction_info` (store_id, account_id, source_id, ref_no, invoice_id, transaction_type, title, details, image, created_by) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $statement->execute(array(store_id(), $account_id, $source_id, $ref_no, $ref, $transaction_type, $title, $details, $image, user_id()));
            $info_id = db()->lastInsertId();

            $statement = db()->prepare("INSERT INTO `bank_transaction_price` (store_id, info_id, ref_no, amount) VALUES (?, ?, ?, ?)");
            $statement->execute(array(store_id(), $info_id, $ref_no, $withdraw_amount));

            $statement = db()->prepare("UPDATE `bank_account_to_store` SET `withdraw` = `withdraw` + {$withdraw_amount} WHERE `store_id` = ? AND `account_id` = ?");
            $statement->execute(array(store_id(), $account_id));

            $statement = db()->prepare("UPDATE `bank_accounts` SET `total_withdraw` = `total_withdraw` + {$withdraw_amount} WHERE `id` = ?");
            $statement->execute(array($account_id));
        }

        echo json_encode(array(
            "success" => true,
            "msg" => "Supplier payment updated successfully"
        ));
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}
