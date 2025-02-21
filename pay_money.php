<?php
session_start();
include 'connect/connection.php';

// ดึงข้อมูลจากตะกร้า
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
$total_price = 0;
$orders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_price += $row['total_price'];
        $orders[] = $row;
    }
} else {
    echo "<script>alert('ไม่มีสินค้าในตะกร้า!'); window.location.href='cart.php';</script>";
    exit();
}

// กดปุ่ม "ชำระเงินแล้ว"
if (isset($_POST['confirm_payment'])) {
    $payment_method = "cash"; // กำหนดเป็นเงินสด
    $order_id = uniqid('ORDER_'); // สร้างรหัสคำสั่งซื้อ

    foreach ($orders as $order) {
        $stmt = $conn->prepare("INSERT INTO orders_admin (order_id, menu_name, noodle, spicy, fermented_fish, pachana, topping, count, total_price, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'waiting')");

        $stmt->bind_param(
            "sssssssids",
            $order_id,
            $order['menu_name'],
            $order['noodle'],
            $order['spicy'],
            $order['fermented_fish'],
            $order['pachana'],
            $order['topping'],
            $order['count'],
            $order['total_price'],
            $payment_method
        );

        $stmt->execute();
        $stmt->close();
    }

    // ล้างตะกร้าหลังบันทึก
    $conn->query("DELETE FROM orders");

    // ส่งไปหน้า wait.php พร้อม order_id
    header("Location: wait.php?order_id=$order_id");
    exit();
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ชำระเงินสด</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body1>
    <div class="container-pay-money">
        <h2>ชำระเงินสด</h2>
        <p>ยอดรวมที่ต้องชำระ: <strong><?php echo number_format($total_price, 2); ?> บาท</strong></p>

        <form method="POST">
            <button type="submit" name="confirm_payment" class="confirm-btn-pay">ชำระเงินแล้ว</button>
        </form>

        <a href="cart.php" class="cancel-btn-pay">ย้อนกลับ</a>
    </div>
</body1>

</html>