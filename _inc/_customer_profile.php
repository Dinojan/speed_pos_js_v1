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

// function date_range_filter_order_payment($from, $to)
// {
//     $from = $from ? $from : date('Y-m-d');
//     $to = $to ? $to : date('Y-m-d');
//     $where_query = '';
//     if (($from && ($to == false)) || ($from == $to)) {
//         $day = date('d', strtotime($from));
//         $month = date('m', strtotime($from));
//         $year = date('Y', strtotime($from));
//         $where_query .= " AND DAY(`payments`.`created_at`) = {$day}";
//         $where_query .= " AND MONTH(`payments`.`created_at`) = {$month}";
//         $where_query .= " AND YEAR(`payments`.`created_at`) = {$year}";
//     } else {
//         $from = date('Y-m-d H:i:s', strtotime($from . ' ' . '00:00:00'));
//         $to = date('Y-m-d H:i:s', strtotime($to . ' ' . '23:59:59'));
//         $where_query .= " AND created_at >= '{$from}' AND created_at <= '{$to}'";
//     }
//     return $where_query;
// }

if ($request->server['REQUEST_METHOD'] == 'GET' && isset($request->get['action_type']) && $request->get['action_type'] == 'GET_TABLE_DATA') {
    try {
        $customerId = $request->get['customer'] ?? null;
        $orderId = $request->get['order'] ?? null;
        $filter = $request->get['filter'] ?? 'all';
        $table = $request->get['table'];
        $isdeleted = $request->get['isdeleted'] ?? 0;

        $from = from();
        $to = to();
        $where = date_range_filter($from, $to, 'payments.');
        $data = [];
        if ($filter === 'order' && $orderId) {
            $sql = "SELECT payments.id, payments.order_id, payments.order_no, payments.c_id, payments.amount, payments.note, payments.created_by, payments.created_at,
               orders.order_details FROM payments LEFT JOIN orders ON payments.order_id = orders.id  WHERE payments.order_id = ? $where";
            $params = $orderId;
        } else if ($filter === 'all' && $table == "order") {
            $sql = "SELECT * FROM orders WHERE cus_id = ?";
            $params = $customerId;
        } else if ($filter === 'all' && $table == "payment") {
            $sql = "SELECT payments.id, payments.order_id, payments.order_no, payments.c_id, payments.amount, payments.note, payments.created_by, payments.created_at,
               orders.order_details FROM payments LEFT JOIN orders ON payments.c_id = orders.cus_id  WHERE payments.c_id = ? $where";
            $params = $customerId;
        }

        $stmt = db()->prepare($sql);
        $stmt->execute([$params]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $i = 0;
        foreach ($rows as &$row) {

            if ($table == "payment") {


                $i++;
                $row['row_index'] = $i;



                if (isset($row['created_at'])) {
                    $row['created_at'] = date("d F, Y / h:i A", strtotime($row['created_at']));
                }

                if ($row['note'] == "") {
                    $row['note'] = "No note";
                }

                if (isset($row['payment_date'])) {
                    $row['payment_date'] = date("d F, Y / h:i A", strtotime($row['payment_date']));
                }

                $row['view'] = '<a href="customer_profile.php?customer=' . $row['id'] . '&order=' . $row['id'] . '" class="btn btn-outline-info btn-sm" title="View">
                    <i class="fas fa-eye"></i>
                 </a>';
            }


            if ($table == "order") {
                $i++;
                $row['row_index'] = $i;

                if (isset($row['created_at'])) {
                    $row['created_at'] = date("d F, Y / h:i A", strtotime($row['created_at']));
                }

                $outstanding = $row['total_amt'] - $row['total_paid'];
                $row['outstanding'] = number_format($outstanding, 2);

                $total_amt = number_format($row['total_amt'], 2);
                $row['total_amt'] = $total_amt;

                $total_paid = number_format($row['total_paid'], 2);
                $row['total_paid'] = $total_paid;

                if ($outstanding > 0) {
                    $row['pay'] = '<button id="pay-order-profile-table-btn" class="btn btn-outline-primary btn-sm pay-btn"  title="View"><i class="fas fa-money-bill"></i></button>';
                } else {
                    $row['pay'] = '<button  class="btn btn-outline-secondary btn-sm pay-btn"  title="View"><i class="fas fa-money-bill"></i></button>';
                }
                $row['view'] = '<a href="customer_profile.php?customer=' . $row['cus_id'] . '&order=' . $row['id'] . '" class="btn btn-outline-info btn-sm" title="View">
                    <i class="fas fa-eye"></i>
                 </a>';
            }
        }

        echo json_encode(['success' => true, 'data' => $rows]);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['errorMsg' => $e->getMessage()]);
        exit();
    }
}
