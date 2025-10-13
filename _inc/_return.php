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

// GET_POS_PRODUCT
if (isset($_GET['action_type']) && $_GET['action_type'] == 'GET_POS_PRODUCT') {
    try {
        $product_model = registry()->get('loader')->model('product');
        $barcode = $_GET['barcode'] ?? null;
        $nameOrBarcode = $_GET['nameOrBarcode'] ?? null;

        if (isset($_GET['c_id']) && $_GET['c_id'] != "") {
            $products = $product_model->getProductsCategoryWise($_GET['c_id'], 3);
        } elseif ($barcode || $nameOrBarcode) {
            $products = $product_model->getProductsBySearch($barcode, $nameOrBarcode, 3);
        } else {
            $products = $product_model->getProductsCategoryWise(null, 3);
        }

        foreach ($products as &$p) {
            $statement = db()->prepare("SELECT c_name FROM category WHERE id = ?");
            $statement->execute([$p['material']]);
            $p['material_name'] = $statement->fetch(PDO::FETCH_ASSOC)['c_name'] ?? '';

            $statement = db()->prepare("SELECT c_name FROM category WHERE id = ?");
            $statement->execute([$p['c_id']]);
            $p['category_name'] = $statement->fetch(PDO::FETCH_ASSOC)['c_name'] ?? '';

            $p['supplier'] = get_the_supplier($p['s_id']) ?? '';

            // Fetch sales item
            $statement = db()->prepare("SELECT * FROM sales_item WHERE p_id = ?");
            $statement->execute([$p['id']]);
            $product = $statement->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $p['sold_price'] = $product['price'];
                $p['sold_date'] = $product['created_at'];

                $statement = db()->prepare("SELECT * FROM sales_info WHERE id = ?");
                $statement->execute([$product['sales_id']]);
                $info = $statement->fetch(PDO::FETCH_ASSOC);

                $p['bill_no'] = $info['bill_no'] ?? '';
            } else {
                $p['sold_price'] = null;
                $p['sold_date'] = null;
                $p['bill_no'] = '';
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'msg' => 'Success',
            'products' => $products
        ]);
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errorMsg' => $e->getMessage()]);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action_type']) && $_GET['action_type'] == 'PLACE_RETURN') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);

        $cart = isset($data['cart']) ? $data['cart'] : [];
        $ref  = isset($data['ref']) ? $data['ref'] : '';
        if (!$cart || !$ref) {
            throw new Exception("Invalid data");
        }

        $statement = db()->prepare("SELECT * FROM sales_info WHERE bill_no = ?");
        $statement->execute([$ref]);
        $info = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$info) {
            throw new Exception("This bill number does not exist");
        }

        // Check if each product in cart belongs to that bill
        $checkStmt = db()->prepare("SELECT * FROM sales_item WHERE p_id = ? AND sales_id = ?");
        foreach ($cart as $item) {
            $checkStmt->execute([$item['id'], $info['id']]);
            // $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($checkStmt->rowCount() == 0) {
                throw new Exception("One or more products do not match the selected bill number");
            }
        }

        // Insert into invoice_info
        $stmt = db()->prepare("DELETE FROM sales_item WHERE p_id = ? AND sales_id = ?");
        foreach ($cart as $item) {
            $stmt->execute([
                $item['id'],
                $info['id']
            ]);
        }

        $statement = db()->prepare("SELECT * FROM sales_item WHERE sales_id = ?");
        $statement->execute([$info['id']]);

        if ($statement->rowCount() == 0) {
            $stmt = db()->prepare("DELETE FROM sales_info WHERE id = ?");
            $stmt->execute([$info['id']]);
        }

        // Update product status
        foreach ($cart as $product) {
            $product_model->updateProductStatus($product['id'], 0);
        }

        echo json_encode([
            "status" => "success",
            "msg" => "Returned successfully",
        ]);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
    }
}
