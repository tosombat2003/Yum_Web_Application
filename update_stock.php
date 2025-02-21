<?php
include 'connect/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $menu_id = intval($_POST["menu_id"]);
    $action = $_POST["action"];
    $amount = intval($_POST["amount"]);

    // ดึงค่า stock ปัจจุบัน
    $query = "SELECT stock FROM menu WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $stmt->bind_result($current_stock);
    $stmt->fetch();
    $stmt->close();

    if ($action === "increase") {
        $new_stock = $current_stock + $amount;
    } elseif ($action === "decrease") {
        if ($current_stock >= $amount) {
            $new_stock = $current_stock - $amount;
        } else {
            echo json_encode(["success" => false, "message" => "Stock ไม่พอ"]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "คำสั่งไม่ถูกต้อง"]);
        exit;
    }

    // อัปเดต stock ในฐานข้อมูล
    $update_query = "UPDATE menu SET stock = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $new_stock, $menu_id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "new_stock" => $new_stock]);
    } else {
        echo json_encode(["success" => false, "message" => "ไม่สามารถอัปเดต stock ได้"]);
    }
    $stmt->close();
}

$conn->close();
?>