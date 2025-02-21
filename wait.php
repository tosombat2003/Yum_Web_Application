<?php
session_start();
include 'connect/connection.php';

$order_id = $_GET['order_id'] ?? '';

// ตรวจสอบสถานะออเดอร์
$sql = "SELECT status FROM orders_admin WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$stmt->bind_result($status);
$stmt->fetch();
$stmt->close();

// ถ้าออเดอร์เสร็จแล้ว ให้แสดงปุ่มกลับหน้าหลัก
$is_done = ($status === 'done');
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รออาหาร</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div class="container">
        <h2>กรุณารออาหารของคุณ</h2>
        <p>หมายเลขคำสั่งซื้อ: <strong><?php echo htmlspecialchars($order_id); ?></strong></p>
        <p>สถานะ: <strong><?php echo ($is_done) ? "ทำอาหารเสร็จแล้ว 🎉" : "กำลังทำอาหาร... ⏳"; ?></strong></p>

        <?php if ($is_done) { ?>
            <a href="home.html" class="btn">กลับหน้าหลัก</a>
        <?php } else { ?>
            <p>โปรดรอ แอดมินจะอัปเดตสถานะเมื่อทำอาหารเสร็จ 🍜</p>
        <?php } ?>
    </div>
</body>

</html>