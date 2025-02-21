<?php
session_start();
include 'connect/connection.php';

// ตรวจสอบว่ามีการส่งค่าผ่าน POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // รับค่าจากฟอร์ม
    $menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;
    $menu_name = isset($_POST['menu_name']) ? $_POST['menu_name'] : '';
    $menu_price = isset($_POST['menu_price']) ? floatval($_POST['menu_price']) : 0;
    $quantity = isset($_POST['count']) ? intval($_POST['count']) : 1; // รับค่าจำนวน

    $noodle = isset($_POST['noodle']) ? $_POST['noodle'] : null;
    $spicy = isset($_POST['spicy']) ? $_POST['spicy'] : null;
    $fermented_fish = isset($_POST['fermented_fish']) ? $_POST['fermented_fish'] : null;
    $pachana = isset($_POST['pachana']) ? $_POST['pachana'] : null;

    // เช็คและรวมท็อปปิ้ง
    $toppings = isset($_POST['topping']) ? $_POST['topping'] : [];
    $topping_list = []; // เก็บชื่อ + ราคา
    $total_price = $menu_price * $quantity; // คำนวณราคาจากจำนวนที่สั่ง

    if (!empty($toppings)) {
        // ดึงข้อมูล topping จากฐานข้อมูล
        $topping_ids = implode("','", array_map('htmlspecialchars', $toppings));
        $topping_sql = "SELECT name, price FROM topping WHERE name IN ('$topping_ids')";
        $topping_result = $conn->query($topping_sql);

        while ($row = $topping_result->fetch_assoc()) {
            $topping_list[] = $row['name'] . " (" . $row['price'] . ")"; // เก็บชื่อและราคา
            $total_price += $row['price'] * $quantity; // บวกราคา topping ตามจำนวนที่สั่ง
        }
    }

    $topping_string = !empty($topping_list) ? implode(", ", $topping_list) : null;

    // เพิ่มข้อมูลลงตาราง orders
    $order_sql = "INSERT INTO orders (menu_id, menu_name, menu_price, noodle, spicy, fermented_fish, pachana, topping, total_price, count) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("isdsssssid", $menu_id, $menu_name, $menu_price, $noodle, $spicy, $fermented_fish, $pachana, $topping_string, $total_price, $quantity);

    // บันทึกข้อมูลลงฐานข้อมูล
    if ($stmt->execute()) {
        echo "<script>alert('บันทึกคำสั่งซื้อสำเร็จ!'); window.location.href='menu.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง";
}
?>