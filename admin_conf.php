<?php
session_start();
include 'connect/connection.php';

// ดึงข้อมูลออเดอร์ที่กำลังรอทำ
$sql = "SELECT * FROM orders_admin WHERE status = 'waiting' ORDER BY order_id";
$result = $conn->query($sql);

$orders = [];
while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [];
    }
    $orders[$order_id][] = $row;
}

if (isset($_POST['finish_order'])) {
    $order_id = $_POST['order_id'];
    $conn->query("UPDATE orders_admin SET status = 'done' WHERE order_id = '$order_id'");
    echo "<script>alert('อัพเดตสถานะสำเร็จ!'); window.location.href='admin.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำสั่งซื้อ</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <a href="#"><img src="pic/logo.png" alt="logo"></a>
            </div>
            <ul class="menu">
                <li><a href="admin.php" class="">Stock</a></li>
                <li><a href="admin_conf.php" class="active">Order</a></li>
                <li><a href="admin_transaction.php" class="">Transaction</a></li>
            </ul>
        </nav>
    </div>

    <h2>คำสั่งซื้อที่รอทำ</h2>

    <?php if (!empty($orders)) { ?>
        <?php foreach ($orders as $order_id => $items) { ?>
            <table border="1">
                <thead>
                    <tr>
                        <th colspan="8">Order ID: <?php echo $order_id; ?></th>
                    </tr>
                    <tr>
                        <th>ชื่อเมนู</th>
                        <th>เส้น</th>
                        <th>ความเผ็ด</th>
                        <th>ปลาร้า</th>
                        <th>ภาชนะ</th>
                        <th>ท็อปปิ้ง</th>
                        <th>จำนวน</th>
                        <th>ราคารวม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item) { ?>
                        <tr>
                            <td><?php echo $item['menu_name']; ?></td>
                            <td><?php echo $item['noodle']; ?></td>
                            <td><?php echo $item['spicy']; ?></td>
                            <td><?php echo $item['fermented_fish']; ?></td>
                            <td><?php echo $item['pachana']; ?></td>
                            <td><?php echo !empty($item['topping']) ? $item['topping'] : '-'; ?></td>
                            <td><?php echo $item['count']; ?></td>
                            <td><?php echo number_format($item['total_price'], 2); ?> บาท</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <form method="POST">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <center>
                    <button type="submit" name="finish_order" class="btn">ทำอาหารเสร็จแล้ว</button>
                </center>
            </form>
            <br>
        <?php } ?>
    <?php } else { ?>
        <p>ไม่มีคำสั่งซื้อที่รอทำ</p>
    <?php } ?>
</body>

</html>