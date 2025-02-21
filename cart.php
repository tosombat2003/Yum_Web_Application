<?php
session_start();
include 'connect/connection.php';

// ลบสินค้ารายชิ้น
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM orders WHERE id=$id");
    header("Location: cart.php");
    exit();
}

// ล้างตะกร้าทั้งหมด
if (isset($_GET['clear'])) {
    $conn->query("DELETE FROM orders");
    header("Location: cart.php");
    exit();
}

// ดึงข้อมูลจากตาราง orders
$sql = "SELECT id, menu_name, menu_price, noodle, spicy, fermented_fish, pachana, topping, count, total_price FROM orders";
$result = $conn->query($sql);
if (isset($_GET['confirm'])) {
    // ดึงข้อมูลจากตาราง orders
    $sql = "SELECT menu_name, topping, count FROM orders";
    $result = $conn->query($sql);


}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
                <li><a href="cart.php" class="active"><i class="fas fa-shopping-cart"></i></a></li>
                <li><a href="home.html">หน้าหลัก</a></li>
                <li><a href="menu.php">เมนู</a></li>
                <li><a href="#">TH|EN</a></li>
            </ul>
        </nav>
    </div>

    <div class="cart-container">
        <h2>สินค้าในตะกร้าสินค้า</h2>
        <?php if ($result->num_rows > 0) { ?>
            <table>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่ออาหาร</th>
                    <th>เส้น</th>
                    <th>ความเผ็ด</th>
                    <th>ปลาร้า</th>
                    <th>ภาชนะ</th>
                    <th>ท็อปปิ้ง</th>
                    <th>จำนวน</th>
                    <th>ราคาต่อหน่วย</th>
                    <th>ราคารวม</th>

                </tr>
                <?php
                $total_price = 0;
                $index = 1;
                while ($row = $result->fetch_assoc()) {
                    $total_price += $row['total_price'];
                    ?>
                    <tr>
                        <td><?php echo $index++; ?></td>
                        <td><?php echo htmlspecialchars($row['menu_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['noodle']); ?></td>
                        <td><?php echo htmlspecialchars($row['spicy']); ?></td>
                        <td><?php echo htmlspecialchars($row['fermented_fish']); ?></td>
                        <td><?php echo htmlspecialchars($row['pachana']); ?></td>
                        <td><?php echo !empty($row['topping']) ? htmlspecialchars($row['topping']) : "-"; ?></td>
                        <td><?php echo $row['count']; ?></td>
                        <td><?php echo number_format($row['menu_price'], 2); ?> บาท</td>
                        <td><?php echo number_format($row['total_price'], 2); ?> บาท</td>
                        <td>
                            <a href="cart.php?delete=<?php echo $row['id']; ?>"
                                onclick="return confirm('ต้องการลบสินค้านี้หรือไม่?');">
                                ❌
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="9"><strong>ยอดรวมทั้งหมด</strong></td>
                    <td><strong><?php echo number_format($total_price, 2); ?> บาท</strong></td>
                    <td></td>
                </tr>
            </table>
            <br>
            <a href="cart.php?clear=true" class="clear-cart"
                onclick="return confirm('ต้องการลบสินค้าทั้งหมดหรือไม่?');">ล้างตะกร้าทั้งหมด</a>
        <?php } else { ?>
            <p>ไม่มีสินค้าในตะกร้า</p>
        <?php } ?>
    </div>
    <center>
        <a href="payment.html" class="btn">ยืนยันคำสั่งซื้อ</a>
    </center>

</body>

</html>

<?php $conn->close(); ?>