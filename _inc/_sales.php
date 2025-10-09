<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}
$customer_model = registry()->get('loader')->model('customer');
$product_model = registry()->get('loader')->model('product');

function gererateUniqueId()
{
    $prepix = "NJV_SALE_";
    $statement = db()->prepare("SELECT * FROM sales_info ORDER BY id DESC LIMIT 1");
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $id = $row["id"];
        $code = 1001 + $id;
    } else {
        $code = 1001;
    }
    $unique_id = $prepix . $code;
    return $unique_id;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action_type']) && $_GET['action_type'] == 'PLACE_SALES') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);

        $cart = isset($data['cart']) ? $data['cart'] : [];
        $ref  = isset($data['ref']) ? $data['ref'] : '';
        $payment = isset($data['payment']) ? $data['payment'] : null;
        if (!$cart || !$ref) {
            throw new Exception("Invalid data");
        }

        $statement = db()->prepare("SELECT * FROM sales_info WHERE bill_no = ?");
        $statement->execute([$ref]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['status'] == 0) {
            throw new Exception("Reference or bill number already exist");
        } else if ($result) {
            throw new Exception("Reference or bill number already returned");
        }


        $unique_no = gererateUniqueId();

        // Insert into invoice_info
        $stmt = db()->prepare("INSERT INTO sales_info 
            (`sales_no`, `bill_no`, `c_id`, `created_by`) 
            VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $unique_no,
            $ref,
            1,
            user_id()
        ]);
        $invoice_id = db()->lastInsertId();

        // Insert invoice items
        $stmt = db()->prepare("INSERT INTO sales_item 
            (`sales_id`, `p_id`, `wgt`, `qty`, `price`) 
            VALUES (?, ?, ?, ?, ?)");
        foreach ($cart as $item) {
            $stmt->execute([
                $invoice_id,
                $item['id'],
                $item['wgt'],
                1,
                $item['material_price']
            ]);
        }

        // Update product status
        foreach ($cart as $product) {
            $product_model->updateProductStatus($product['id'], 3);
        }

        echo json_encode([
            "status" => "success",
            "msg" => "Order placed successfully",
            "invoice_no" => $unique_no
        ]);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
    }
}
