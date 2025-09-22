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

// stock checking history model
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CHECKED_HISTORY') {
    try {
        $id = $request->get['product'];

        $statement = db()->prepare("SELECT * FROM product WHERE id = ?");
        $statement->execute([$id]);
        $product = $statement->fetch(PDO::FETCH_ASSOC);

        $statement = db()->prepare("SELECT created_at, checked_by FROM stock_checking WHERE p_id = ? ORDER BY created_at DESC");
        $statement->execute([$id]);
        $history = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = db()->prepare("SELECT c_name FROM category WHERE id = ?");
        $statement->execute([$product['material']]);
        $material = $statement->fetch(PDO::FETCH_ASSOC)['c_name'];

        $supplier = get_the_supplier($product["s_id"]);

        if (!empty($history)) {
            $last_checked = date("d F, Y / H:i", strtotime($history[0]['created_at']));
        } else {
            $last_checked = "Not checked yet";
        }

        if ($product['status'] == 0) {
            $status = '<span class="badge bg-success p-1">For sale</span>';
        } else if ($product['status'] == 2) {
            $status = '<span class="badge bg-danger p-1">Deleted</span>';
        } else if ($product['status'] == 3) {
            $status = '<span class="badge bg-secondary p-1">Sold</span>';
        } else {
            $status = '<span class="badge bg-warning p-1">Not for sale</span>';
        }


        include "template/stock_checked_history.php";
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

// product details model
if (isset($request->get['action_type']) && $request->get['action_type'] == 'GET_DETAILS') {
    try {
        $id = $request->get['product'];

        $statement = db()->prepare("SELECT * FROM product WHERE id = ?");
        $statement->execute([$id]);
        $product = $statement->fetch(PDO::FETCH_ASSOC);

        $statement = db()->prepare("SELECT c_name FROM category WHERE id = ?");
        $statement->execute([$product['material']]);
        $material = $statement->fetch(PDO::FETCH_ASSOC)['c_name'];

        $statement = db()->prepare("SELECT c_name FROM category WHERE id = ?");
        $statement->execute([$product['c_id']]);
        $category = $statement->fetch(PDO::FETCH_ASSOC)['c_name'];

        $supplier = get_the_supplier($product["s_id"]);


        if ($product['status'] == 0) {
            $status = '<span class="badge bg-success p-1">For sale</span>';
        } else if ($product['status'] == 2) {
            $status = '<span class="badge bg-danger p-1">Deleted</span>';
        } else if ($product['status'] == 3) {
            $status = '<span class="badge bg-secondary p-1">Sold</span>';
        } else {
            $status = '<span class="badge bg-warning p-1">Not for sale</span>';
        }

        if ($product['status'] == 3) {
            $statement = db()->prepare(" SELECT ip.sub_total AS sub_total, ii.invoice_no AS invoice_no, ii.ref_no AS ref_no, ii.cus_name AS cus_name, ii.cus_mobile AS cus_mobile, ii.cus_address AS cus_address, ii.created_at AS invoice_date FROM invoice_item ip JOIN invoice_info ii ON ip.invoice_no = ii.invoice_no WHERE ip.p_id = ?");
            $statement->execute([$id]);
            $invoice = $statement->fetch(PDO::FETCH_ASSOC);
        }


        include "template/product_datails_view.php";
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
    if (!validateString($request->post['p_name'])) {
        throw new Exception(trans('error_product_name'));
    }

    if (!validateString($request->post['p_code'])) {
        throw new Exception(trans('error_product_code'));
    }

    if (!validateInteger($request->post['c_id'])) {
        throw new Exception(trans('error_product_category'));
    }

    if (!validateInteger($request->post['s_id'])) {
        throw new Exception(trans('error_product_supplier'));
    }

    if (empty($request->post['wgt'])) {
        throw new Exception(trans('error_product_weight'));
    }
    if (empty($request->post['cost'])) {
        throw new Exception(trans('error_product_cost'));
    }
}
// Check existance by id
function validate_existance($request, $id = 0)
{
    $statement = db()->prepare("SELECT * FROM `product` WHERE `p_code` = ? AND `id` != ?");
    $statement->execute(array($request->post['p_code'], $id));
    if ($statement->rowCount() > 0) {
        throw new Exception(trans('error_product_code_exist'));
    }
}
// CREATE product 
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE') {
    try {

        // Check create permission
        if (user_group_id() != 1 && !has_permission('access', 'create_product')) {
            throw new Exception(trans('error_create_permission'));
        }

        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request);

        // add role
        $stock_id = $product_model->addproduct($request->post);

        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_successful_created'), 'id' => $stock_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'update_product')) {
            throw new Exception(trans('error_update_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_stock_id'));
        }

        $id = $request->post['id'];
        // Validate post data
        validate_request_data($request);

        // Validate existance
        validate_existance($request, $id);
        // Edit role
        $stock_id = $product_model->editproduct($id, $request->post);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans('text_update_success'), 'id' => $stock_id));
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
        if (user_group_id() != 1 && !has_permission('access', 'delete_product')) {
            throw new Exception(trans('error_delete_permission'));
        }
        if (empty($request->post['id'])) {
            throw new Exception(trans('error_stock_id'));
        }
        $id = $request->post['id'];
        $checkProduct = $product_model->getproduct($id);
        if ($checkProduct['status'] == 2) {
            $sts = 0;

            $msg = 'text_restore_success';
        } else {
            $sts = 2;
            $msg = 'text_move_to_bin_success';
        }

        $product = $product_model->updateProductStatus($id, $sts);
        header('Content-Type: application/json');
        echo json_encode(array('msg' => trans($msg), 'id' => $id));
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
    include 'template/product_create_form.php';
    exit();
}
//edit form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
    try {
        if (empty($request->get['id'])) {
            throw new Exception(trans('error_stock_id'));
        }
        $id = $request->get['id'];
        $product = $product_model->getproduct($id);
        if (!$product) {
            throw new Exception(trans('error_product_not_found'));
        }
        include 'template/product_edit_form.php';
        exit();
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('errorMsg' => $e->getMessage()));
        exit();
    }
}

// GET_POS_PRODUCT
if (isset($_GET['action_type']) && $_GET['action_type'] == 'GET_POS_PRODUCT') {
    try {
        $product_model = registry()->get('loader')->model('product');
        $barcode = $_GET['barcode'] ?? null;
        $nameOrBarcode = $_GET['nameOrBarcode'] ?? null;

        if (isset($_GET['c_id']) && $_GET['c_id'] != "") {
            $products = $product_model->getProductsCategoryWise($_GET['c_id']);
        } elseif ($barcode || $nameOrBarcode) {
            $products = $product_model->getProductsBySearch($barcode, $nameOrBarcode);
        } else {
            $products = $product_model->getProductsCategoryWise();
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

if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_TABLE_DATA") {
    try {
        $data = array();
        $where = "WHERE 1=1";

        if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
            $where .= " AND status = 2";
        } else {
            $where .= " AND status != 2";
        }

        $statement = db()->prepare("SELECT * FROM product $where");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;

            $row['category'] = get_the_category($row['c_id'])['c_name'];
            $row['supplier'] = get_the_supplier($row['s_id'])['s_name'] . " (" . format_mobile(get_the_supplier($row['s_id'])['s_mobile']) . ")";



            $row['view'] = '<button id="view-product-details" class="btn btn-outline-info btn-sm view-btn" title="View"  data-id="' . $row['id'] . '"><i class="fas fa-eye"></i></button>';

            //if ($row['id'] != 1) {
            if ($row['status'] == 2 || $row['status'] == 3) {
                $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn"  title="Edit" disabled><i class="fas fa-edit"></i></button>';
            } else {
                $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            }
            // } else {
            //     $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            // }
            if ($row['status'] == 3) {
                $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete" disabled><i class="fas fa-trash-alt"></i></button>';
            } else if ($row['status'] == 2) {
                $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-undo"></i></button>';
            } else {
                $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
            }
            //     $row['delete'] = '<button class="btn btn-outline-danger btn-sm delete-btn" disabled title="Delete"><i class="fas fa-trash-alt"></i></button>';
            // }
            if ($row['status'] == 0) {
                $row['sts'] = '<span class="badge bg-success p-1">For sale</span>';
            } else if ($row['status'] == 2) {
                $row['sts'] = '<span class="badge bg-danger p-1">Deleted</span>';
            } else if ($row['status'] == 3) {
                $row['sts'] = '<span class="badge bg-secondary p-1">Sold</span>';
            } else {
                $row['sts'] = '<span class="badge bg-warning p-1">Not for sale</span>';
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


if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_SUP_JEWELS") {
    try {
        $data = array();
        $where = "WHERE 1=1";

        $sup_id =  $request->get['sup'];
        $where .= " AND $sup_id";

        // if (isset($request->get['isdeleted']) && $request->get['isdeleted'] == 2) {
        //     $where .= " AND status = 2";
        // } else {
        //     $where .= " AND status != 2";
        // }

        $statement = db()->prepare("SELECT * FROM product $where");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;

            $row['category'] = get_the_category($row['c_id'])['c_name'];
            $row['supplier'] = get_the_supplier($row['s_id'])['s_name'] . " (" . format_mobile(get_the_supplier($row['s_id'])['s_mobile']) . ")";



            $row['view'] = '<button id="view-product-details" class="btn btn-outline-info btn-sm view-btn" title="View"  data-id="' . $row['id'] . '"><i class="fas fa-eye"></i></button>';

            //if ($row['id'] != 1) {
            if ($row['status'] == 2 || $row['status'] == 3) {
                $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn"  title="Edit" disabled><i class="fas fa-edit"></i></button>';
            } else {
                $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn"  title="Edit"><i class="fas fa-edit"></i></button>';
            }
            // } else {
            //     $row['edit'] = '<button id="edit-product" class="btn btn-outline-success btn-sm edit-btn" disabled  title="Edit"><i class="fas fa-edit"></i></button>';
            // }
            if ($row['status'] == 3) {
                $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete" disabled><i class="fas fa-trash-alt"></i></button>';
            } else if ($row['status'] == 2) {
                $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-undo"></i></button>';
            } else {
                $row['delete'] = '<button id="delete-product" class="btn btn-outline-danger btn-sm delete-btn"  title="Delete"><i class="fas fa-trash-alt"></i></button>';
            }
            //     $row['delete'] = '<button class="btn btn-outline-danger btn-sm delete-btn" disabled title="Delete"><i class="fas fa-trash-alt"></i></button>';
            // }
            if ($row['status'] == 0) {
                $row['sts'] = '<span class="badge bg-success p-1">For sale</span>';
            } else if ($row['status'] == 2) {
                $row['sts'] = '<span class="badge bg-danger p-1">Deleted</span>';
            } else if ($row['status'] == 3) {
                $row['sts'] = '<span class="badge bg-secondary p-1">Sold</span>';
            } else {
                $row['sts'] = '<span class="badge bg-warning p-1">Not for sale</span>';
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

if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_STOCKS") {
    try {
        $data = array();
        $where = "WHERE 1=1";

        // throw new Exception($_GET['status']);
        if ($_GET['status'] && $_GET['status'] != "all") {
            $where .= " AND status = " . $_GET['status'];
        } else if ($_GET['status'] == 0) {
            $where .= " AND status = 0";
        }
        $statement = db()->prepare("SELECT * FROM product $where");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;

            $row['category'] = get_the_category($row['c_id'])['c_name'];
            $row['supplier'] = get_the_supplier($row['s_id'])['s_name'] . " (" . get_the_supplier($row['s_id'])['s_mobile'] . ")";

            if ($row["karate"] == '' || $row['karate'] == null) {
                $row["karate"] = "Null";
            } else {
                $karate = $row["karate"];
                $row["karate"] = $karate . " k";
            }

            if ($row['status'] == 0) {
                $row['sts'] = '<span class="badge bg-success p-1">For sale</span>';
            } else if ($row['status'] == 2) {
                $row['sts'] = '<span class="badge bg-danger p-1">Deleted</span>';
            } else if ($row['status'] == 3) {
                $row['sts'] = '<span class="badge bg-secondary p-1">Sold</span>';
            } else {
                $row['sts'] = '<span class="badge bg-warning p-1">Not for sale</span>';
            }

            $row["mat"] = get_the_category($row['material'])['c_name'];

            $row['view'] = '<button id="view-product" class="btn btn-outline-info btn-sm view-btn"  title="View"><i class="fas fa-eye"></i></button>';
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

if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CHECKED_STOCK') {
    try {

        // Check create permission
        // if (user_group_id() != 1 && !has_permission('access', 'create_product')) {
        //     throw new Exception(trans('error_create_permission'));
        // }

        if (empty($request->post['search'])) {
            throw new Exception('Barcode is empty!');
        }

        $statement = db()->prepare('SELECT * FROM product WHERE p_code = ?');
        $statement->execute(array($request->post['search']));
        $product = $statement->fetch(PDO::FETCH_ASSOC);
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

// if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->get['stock']) && $request->get['stock'] != '') {
//     try {

//         // Check create permission
//         // if (user_group_id() != 1 && !has_permission('access', 'create_product')) {
//         //     throw new Exception(trans('error_create_permission'));
//         // }

//         $product = $product_model->getProductBySearch($_GET['stock']);

//         $statement = db()->prepare("SELECT * FROM stock_checking WHERE p_code = ?");
//         $statement->execute(array($product['p_code']));

//         if ($statement->rowCount() > 0) {
//             $statement = db()->prepare('UPDATE `stock_checking` SET `checked_count` = `checked_count` + 1 WHERE p_code = ?');
//             $statement->execute(array($product['p_code']));
//             header('Content-Type: application/json');
//             echo json_encode(array('msg' => trans('text_this_item_has_already_been_checked')));
//             exit();
//         } else {
//             $stock_id = $product_model->addCheckedJewels($request->post);
//             header('Content-Type: application/json');
//             echo json_encode(array('msg' => trans('text_item_checked')));
//         }
//     } catch (Exception $e) {

//         header('HTTP/1.1 422 Unprocessable Entity');
//         header('Content-Type: application/json; charset=UTF-8');
//         echo json_encode(array('errorMsg' => $e->getMessage()));
//         exit();
//     }
// }

if ($request->server['REQUEST_METHOD'] == 'GET' && $request->get['action_type'] == "GET_CHECKED_STOCKS") {
    try {
        $data = array();

        $where = "WHERE 1=1 ";
        $from = from();
        $to = to();
        $where .= date_range_filter($from, $to);

        $statement = db()->prepare("SELECT * FROM stock_checking $where ORDER BY created_at DESC");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        $i = 0;
        foreach ($data as &$row) {
            $i++;
            $row["row_index"] = $i;
            $row['checker'] = get_the_user($row['checked_by'], 'username');
            if ($row['p_status'] == 0) {
                $row['sts'] = '<span class="badge bg-success p-1">For sale</span>';
            } else if ($row['p_status'] == 2) {
                $row['sts'] = '<span class="badge bg-danger p-1">Deleted</span>';
            } else if ($row['p_status'] == 3) {
                $row['sts'] = '<span class="badge bg-secondary p-1">Sold</span>';
            } else {
                $row['sts'] = '<span class="badge bg-warning p-1">Not for sale</span>';
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

// GET MISSING PRODUCTS
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action_type']) && $_GET['action_type'] == 'GET_MISSING_PRODUCTS') {
    try {
        $where = ' 1 = 1';
        $from = from();
        $to = to();
        $where .= date_range_filter($from, $to);

        $products = [];
        $checkedProducts = [];
        $missingProducts = [];

        // Get all products with status = 0 or 1
        $statement = db()->prepare("SELECT * FROM product WHERE status IN (0,1)");
        $statement->execute();
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Get checked products (only p_code) within date range
        $statement = db()->prepare("SELECT p_code FROM stock_checking WHERE $where");
        $statement->execute();
        $checkedProducts = $statement->fetchAll(PDO::FETCH_COLUMN);

        // Find missing products
        foreach ($products as $product) {
            if (!in_array($product['p_code'], $checkedProducts)) {
                $missingProducts[] = $product;
            }
        }

        // Format result
        $i = 0;
        foreach ($missingProducts as &$row) {
            $i++;
            $row["row_index"] = $i;
            $row['checker'] = '';

            if ($row['status'] == 0) {
                $row['sts'] = '<span class="badge bg-success p-1">For sale</span>';
            } else if ($row['status'] == 2) {
                $row['sts'] = '<span class="badge bg-danger p-1">Deleted</span>';
            } else if ($row['status'] == 3) {
                $row['sts'] = '<span class="badge bg-secondary p-1">Sold</span>';
            } else {
                $row['sts'] = '<span class="badge bg-warning p-1">Not for sale</span>';
            }

            $row['view'] = '<button id="view-checked-history" class="btn btn-outline-primary" data-id="' . $row['id'] . '"><i class="fas fa-history"></i></button>';
        }

        echo json_encode([
            "success" => true,
            "data" => array_values($missingProducts)
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}
