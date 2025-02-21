<?php
session_start();
include 'connect/connection.php';

// ตรวจสอบค่ากรองวันที่
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// สร้าง SQL ตามเงื่อนไข
$sql = "SELECT order_id, 
               GROUP_CONCAT(DISTINCT menu_name SEPARATOR ', ') AS menus, 
               SUM(total_price) AS total_price, 
               order_time
        FROM orders_admin";

// ถ้าผู้ใช้เลือกช่วงวันที่ ให้เพิ่มเงื่อนไข WHERE
if (!empty($start_date) && !empty($end_date)) {
    $sql .= " WHERE DATE(order_time) BETWEEN ? AND ?";
}

// จัดกลุ่มออเดอร์ที่มี order_id เดียวกัน
$sql .= " GROUP BY order_id ORDER BY id ASC";

// เตรียมและดำเนินการคำสั่ง SQL
$stmt = $conn->prepare($sql);
if (!empty($start_date) && !empty($end_date)) {
    $stmt->bind_param("ss", $start_date, $end_date);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บันทึกการขาย</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <a href="#"><img src="pic/logo.png" alt="logo"></a>
            </div>
            <ul class="menu">
                <li><a href="admin.php">Stock</a></li>
                <li><a href="admin_conf.php">Order</a></li>
                <li><a href="admin_transaction.php" class="active">Transaction</a></li>
            </ul>
        </nav>
    </div>

    <div class="container">
        <h2>บันทึกการขาย</h2>

        <!-- ฟอร์มเลือกช่วงวันที่ -->
        <form method="GET">
            <label for="start_date">วันที่เริ่มต้น:</label>
            <input type="date" name="start_date" value="<?php echo $start_date; ?>">
            <label for="end_date">ถึง:</label>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>">
            <button type="submit">กรอง</button>
        </form>

        <table border="1">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>รายการอาหาร</th>
                    <th>ยอดรวม (บาท)</th>
                    <th>เวลาสั่งซื้อ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['menus'] ?: '-'; ?></td>
                        <td><?php echo number_format($row['total_price'] ?: 0, 2); ?> บาท</td>
                        <td><?php echo $row['order_time']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>