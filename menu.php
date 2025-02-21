<?php
session_start();
include 'connect/connection.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าการเข้ารหัสภาษาไทย
$conn->set_charset("utf8mb4");

// ดึงข้อมูลเมนูจากฐานข้อมูล
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <a href="#"><img src="pic/logo.png" alt="โลโก้ร้านอาหาร"></a>
            </div>
            <ul class="menu">
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i></a></li>
                <li><a href="home.html">หน้าหลัก</a></li>
                <li><a href="menu.php" class="active">เมนู</a></li>
                <li><a href="#">TH|EN</a></li>
            </ul>
        </nav>
    </div>

    <!-- แสดงเมนู -->
    <section class="bottom-section">
        <div class="content1">
            <h2>เมนูอาหาร</h2>
            <div class="menu-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="menu-item">
                            <img src="pic/<?= htmlspecialchars($row['image']) ?>"
                                alt="รูปภาพของ <?= htmlspecialchars($row['name']) ?>">
                            <p>ราคา: <?= htmlspecialchars($row['price']) ?> บาท</p>
                            <p><?= htmlspecialchars($row['name']) ?></p>
                            <a href="details.php?menu_id=<?= htmlspecialchars($row['id']) ?>" class="btn">เลือกซื้อ</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>ไม่มีเมนูอาหารในขณะนี้</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</body>

</html>