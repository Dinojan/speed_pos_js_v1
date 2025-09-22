<?php
require_once('../_init.php');
if (!is_loggedin()) {
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => trans('error_login')));
    exit();
}

function get_material_price($id, $karate = null)
{
    $sql = "SELECT price, karate FROM material_price WHERE m_id = ?";
    $params = [$id];

    if ($karate !== null) {
        $sql .= " AND karate = ?";
        $params[] = $karate;
    }

    $sql .= " ORDER BY created_at DESC LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action_type']) && $_GET['action_type'] == 'GET_MATERIAL_PRICES') {
    try {
        $gold22 = get_material_price(2, 22)['price'];
        $gold24 = get_material_price(2, 24)['price'];
        $silver = get_material_price(8)['price'];

        echo json_encode([
            "status" => "success",
            "data" => [
                "gold22" => $gold22 ? number_format($gold22, 2, '.', '') : 0,
                "gold24" => $gold24 ? number_format($gold24, 2, '.', '') : 0,
                "silver" => $silver ? number_format($silver, 2, '.', '') : 0
            ]
        ]);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action_type']) && $_GET['action_type'] == 'CREATE') {
    try {


        $stmt = db()->prepare("INSERT INTO material_price (m_id, m_name, price, karate, created_by) VALUES (?, ?, ?, ?, ?)");

        if (isset($_POST['gold22']) && $_POST['gold22'] !== '') {
            $stmt->execute([2, 'Gold', $_POST['gold22'], 22, user_id()]);
        }
        if (isset($_POST['gold24']) && $_POST['gold24'] !== '') {
            $stmt->execute([2, 'Gold', $_POST['gold24'], 24, user_id()]);
        }
        if (isset($_POST['silver']) && $_POST['silver'] !== '') {
            $stmt->execute([8, 'Silver', $_POST['silver'], null, user_id()]);
        }

        $gold22 = get_material_price(2, 22)['price'];
        $gold24 = get_material_price(2, 24)['price'];
        $silver = get_material_price(8)['price'];
        // Return JSON
        echo json_encode([
            "status" => "success",
            "data" => [
                "gold22" => $gold22,
                "gold24" => $gold24,
                "silver" => $silver
            ]
        ]);
    } catch (Exception $e) {
        header('HTTP/1.1 422 Unprocessable Entity');
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}
