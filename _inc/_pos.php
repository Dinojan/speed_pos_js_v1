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

if (isset($request->get['action_type']) && $request->get['action_type'] == 'SELECT') {
    try {
        include 'template/select_customer_form.php';
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

function gererateUniqueId()
{
    $prepix = "SPJ";
    $statement = db()->prepare("SELECT * FROM invoice_info ORDER BY id DESC LIMIT 1");
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

// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action_type']) && $_GET['action_type'] == 'PLACE_ORDER') {

//     try {
//         // JSON payload read
//         $payload = json_decode(file_get_contents("php://input"), true);

//         if (!$payload) {
//             echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
//             exit;
//         }

//         // Extract data
//         $customer   = $payload['customer'];
//         $cart       = $payload['cart'];
//         $payment    = $payload['payment'];
//         $ref        = $payload['ref'];
//         $method     = $payload['paymentMethod'];

//         $unique_no = gererateUniqueId();
//         print_r($payment['advance']);

//         // Insert order table
//         $statement = db()->prepare("INSERT INTO invoice_info (`invoice_no`, `ref_no`, `cus_id`, `cus_name`, `cus_mobile`, `cus_address`, `sub_total`, `discount`, `total_payable`, `total_paid`, `outstanding`, `payment_method`, `created_by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//         $statement->execute([$unique_no, $ref, $customer['id'], $payment['sub_total'], $payment['total_discount'], $payment['final_payment'], $payment['advance'], $payment['outstanding'], $method, user_id()]);
//         $invoice_id = db()->lastInsertId();

//         $statement = db()->prepare("INSERT INTO invoice_item (`invoice_info_id`, `invoice_no`, `p_id`, `p_code`, `wgt`, `amount`, `qty`, `discount`, `sub_total`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
//         foreach ($cart as $item) {
//             $statement->execute([$invoice_id, $unique_no, $item['id'], $item['p_code'], $item['wgt'], $item['material_price'], $item['qty'], $item['discount'], $item['sub_total']]);
//         }

//         $statement = db()->prepare('INSERT INTO `invoice_payment`(`invoice_info_id`, `invoice_no`, `payment_method`, `amount`, `created_by`) VALUES (?, ?, ?, ?, ?)');
//         $statement->execute([$invoice_id, $unique_no, $method, $payment['advance'], $user_id()]);

//         echo json_encode([
//             "status" => "success",
//             "message" => "Order placed successfully",
//             "invoice_no" => $unique_no
//         ]);
//     } catch (Exception $e) {

//         header('HTTP/1.1 422 Unprocessable Entity');
//         header('Content-Type: application/json; charset=UTF-8');
//         echo json_encode(array('errorMsg' => $e->getMessage()));
//         exit();
//     }
// }


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action_type']) && $_POST['action_type'] == 'PLACE_ORDER') {

    try {
        // Decode FormData fields
        $customer = isset($_POST['customer']) ? json_decode($_POST['customer'], true) : null;
        $cart     = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];
        $payment  = isset($_POST['payment']) ? json_decode($_POST['payment'], true) : [];
        $ref      = isset($_POST['ref']) ? $_POST['ref'] : '';
        $method   = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : '';

        if (!$customer || !$payment) {
            throw new Exception("Invalid data");
        }

        $statement = db()->prepare("SELECT * FROM invoice_info WHERE ref_no = ?");
        $statement->execute([$ref]);

        if ($statement->rowCount() > 0) {
            throw new Exception("Reference or bill number already exist");
        }

        $unique_no = gererateUniqueId();
        $user_id = user_id();

        // Insert into invoice_info
        $stmt = db()->prepare("INSERT INTO invoice_info 
            (`invoice_no`, `ref_no`, `cus_id`, `cus_name`, `cus_mobile`, `cus_address`, `sub_total`, `discount`, `total_payable`, `total_paid`, `outstanding`, `payment_method`, `created_by`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $unique_no,
            $ref,
            $customer['id'],
            $customer['name'],
            $customer['mobile'],
            $customer['address'],
            $payment['sub_total'],
            $payment['total_discount'],
            $payment['final_payment'],
            $payment['advance'],
            $payment['outstanding'],
            $method,
            $user_id
        ]);
        $invoice_id = db()->lastInsertId();

        // Insert invoice items
        $stmt = db()->prepare("INSERT INTO invoice_item 
            (`invoice_info_id`, `invoice_no`, `p_id`, `p_code`, `wgt`, `amount`, `qty`, `discount`, `sub_total`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($cart as $item) {
            $stmt->execute([
                $invoice_id,
                $unique_no,
                $item['id'],
                $item['p_code'],
                $item['wgt'],
                $item['material_price'],
                $item['qty'],
                $item['discount'],
                $item['sub_total']
            ]);
            $product = $product_model->updateProductStatus($item['id'], 3);
        }


        // Insert payment
        $stmt = db()->prepare("INSERT INTO invoice_payment 
            (`invoice_info_id`, `invoice_no`, `payment_method`, `amount`, `created_by`) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$invoice_id, $unique_no, $method, $payment['advance'], $user_id]);

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
