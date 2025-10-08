<?php
// 0 - in stock, 1 - not for sale, 2 - deleted, 3 - sold, 4 - missing
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$product_model = registry()->get('loader')->model('product'); // call modal file

function get_bill_no($p_id)
{
    $statement = db()->prepare("SELECT s.bill_no FROM sales_item si JOIN sales_info s ON si.sales_id = s.id WHERE si.p_id = $p_id");
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

// CREATE product 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action_type']) && $_GET['action_type'] === 'ASSIGN') {
    try {
        // Read raw input and decode JSON
        $rawData = file_get_contents('php://input');
        $decoded = json_decode($rawData, true);

        // If sent as formData, handle differently
        if (!$decoded && isset($_POST['products'])) {
            $decoded = json_decode($_POST['products'], true);
        }

        if (!$decoded) {
            throw new Exception('No valid product data received.');
        }

        // print_r($decoded); // for debugging

        // Example loop
        foreach ($decoded as $product) {
            $statement = db()->prepare("SELECT p_code FROM stock_checking WHERE p_code = ?");
            $statement->execute([$product['p_code']]);

            if ($statement->rowCount() > 0) {
                continue;
            }

            $statement = db()->prepare("INSERT INTO stock_checking (p_id, p_code, p_name, checked_by)
                VALUES (?, ?, ?, ?)");
            $statement->execute([
                $product['id'],
                $product['p_code'],
                $product['p_name'],
                user_id()
            ]);
        }

        header('Content-Type: application/json');
        echo json_encode(['msg' => 'Products assigned successfully']);
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errorMsg' => $e->getMessage()]);
        exit();
    }
}


if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CHECKED_STOCK') {
    try {

        // Check create permission
        // if (user_group_id() != 1 && !has_permission('access', 'create_product')) {
        //     throw new Exception(trans('error_create_permission'));
        // }

        // if (empty($request->post['search'])) {
        //     throw new Exception('Barcode is empty!');
        // }

        // $statement = db()->prepare('SELECT * FROM product WHERE p_code = ?');
        // $statement->execute(array($request->post['search']));
        // $product = $statement->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $statement = db()->prepare("SELECT * FROM stock_checking WHERE p_code = ? AND DATE(created_at) = CURDATE()");
            $statement->execute(array($product['p_code']));
            if ($statement->rowCount() > 0) {
                $statement = db()->prepare('UPDATE `stock_checking` SET `checked_count` = `checked_count` + 1 WHERE p_code = ? AND DATE(created_at) = CURDATE()');
                $statement->execute(array($product['p_code']));
                header('Content-Type: application/json');
                echo json_encode(array('msg' => trans('text_this_item_has_already_been_checked'), 'status' => 'warning'));
                exit();
            } else {
                $statement = db()->prepare("INSERT INTO `stock_checking`(`p_id`, `p_code`, `p_name`, `p_status`,  `checked_by`) VALUES ( ?, ?, ?, ?, ?)");
                $statement->execute([$product['id'], $product['p_code'], $product['p_name'], $product['status'],  user_id()]);

                header('Content-Type: application/json');
                echo json_encode(array('msg' => trans('text_item_checked_success'), 'status' => 'success'));
            }
        } else {
            throw new Exception('Product code not found!');
        }
    } catch (Exception $e) {

        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}
