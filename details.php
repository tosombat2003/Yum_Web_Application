<?php
session_start();
include 'connect/connection.php';

// รับค่า menu_id จาก URL (ใช้ prepared statement เพื่อป้องกัน SQL Injection)
$menu_id = isset($_GET['menu_id']) ? $_GET['menu_id'] : 1;

// ดึงข้อมูลเมนู
$menu_sql = "SELECT * FROM menu WHERE id = ?";
$stmt = $conn->prepare($menu_sql);
$stmt->bind_param("i", $menu_id); // 'i' หมายถึง integer
$stmt->execute();
$menu_result = $stmt->get_result();
$menu_item = $menu_result->fetch_assoc();

// ดึงตัวเลือกจากฐานข้อมูล
$noodle_sql = "SELECT * FROM noodle_options";
$noodle_result = $conn->query($noodle_sql);

$spicy_sql = "SELECT * FROM spicy_options";
$spicy_result = $conn->query($spicy_sql);

$fermented_sql = "SELECT * FROM sluttish"; // เปลี่ยนมาใช้ sluttish
$fermented_result = $conn->query($fermented_sql);

$pachana_sql = "SELECT * FROM pachana";
$pachana_result = $conn->query($pachana_sql);

$topping_sql = "SELECT * FROM topping";
$topping_result = $conn->query($topping_sql);

$conn->set_charset("utf8mb4");

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดเมนู</title>
    <link rel="stylesheet" href="stylesheet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <section class="details-menu">
        <h2>รายละเอียดเพิ่มเติม</h2>
        <h1><?php echo htmlspecialchars($menu_item['name']); ?></h1>
        <p class="price">ราคา <?php echo number_format($menu_item['price'], 2); ?> ฿</p>
        <form action="order.php" method="post">
            <!-- ส่งค่าเมนูหลัก -->
            <input type="hidden" name="menu_id" value="<?php echo $menu_id; ?>">
            <input type="hidden" name="menu_name" value="<?php echo htmlspecialchars($menu_item['name']); ?>">
            <input type="hidden" name="menu_price" value="<?php echo $menu_item['price']; ?>">

            <!-- กรอบรวมทุกตัวเลือก -->
            <div class="all-options-container">

                <!-- เลือกเส้น -->
                <h3>เลือกเส้น</h3>
                <div class="noodle-options">
                    <?php while ($noodle = $noodle_result->fetch_assoc()) {
                        $noodle_name = isset($noodle['name']) ? htmlspecialchars($noodle['name']) : "ไม่ทราบชื่อ";
                        $noodle_image = isset($noodle['image']) ? "pic/item/" . htmlspecialchars($noodle['image']) : "pic/default.png";
                        ?>
                        <label>
                            <input type="radio" name="noodle" value="<?php echo $noodle_name; ?>" required>
                            <!-- เพิ่ม required -->
                            <img src="<?php echo $noodle_image; ?>" alt="<?php echo $noodle_name; ?>">
                            <div class="noodle-name"><?php echo $noodle_name; ?></div>
                        </label>
                    <?php } ?>
                </div>

                <!-- เลือกความเผ็ด -->
                <h3>เลือกความเผ็ด</h3>
                <div class="spicy-options">
                    <?php while ($spicy = $spicy_result->fetch_assoc()) {
                        $spicy_name = isset($spicy['level']) ? htmlspecialchars($spicy['level']) : "ไม่ระบุ";
                        $spicy_image = isset($spicy['image']) ? "pic/item/" . htmlspecialchars($spicy['image']) : "pic/default.png";
                        ?>
                        <label>
                            <input type="radio" name="spicy" value="<?php echo $spicy_name; ?>" required>
                            <!-- เพิ่ม required -->
                            <img src="<?php echo $spicy_image; ?>" alt="<?php echo $spicy_name; ?>">
                            <div class="spicy-level"><?php echo $spicy_name; ?></div>
                        </label>
                    <?php } ?>
                </div>

                <!-- ปลาร้า -->
                <h3>ปลาร้า</h3>
                <div class="fermented-options">
                    <?php while ($fermented = $fermented_result->fetch_assoc()) {
                        $fermented_name = isset($fermented['option_sluttish']) ? htmlspecialchars($fermented['option_sluttish']) : "ไม่ระบุ";
                        $fermented_image = isset($fermented['image']) ? "pic/item/" . htmlspecialchars($fermented['image']) : "pic/default.png";
                        ?>
                        <label>
                            <input type="radio" name="fermented_fish" value="<?php echo $fermented_name; ?>" required>
                            <!-- เพิ่ม required -->
                            <img src="<?php echo $fermented_image; ?>" alt="<?php echo $fermented_name; ?>">
                            <div class="fermented-level"><?php echo $fermented_name; ?></div>
                        </label>
                    <?php } ?>
                </div>

                <!-- ภาชนะ -->
                <h3>ภาชนะ</h3>
                <div class="pachana-options">
                    <?php while ($pachana = $pachana_result->fetch_assoc()) {
                        $pachana_name = isset($pachana['option_name']) ? htmlspecialchars($pachana['option_name']) : "ไม่ระบุ";
                        $pachana_image = isset($pachana['image']) ? "pic/item/" . htmlspecialchars($pachana['image']) : "pic/default.png";
                        ?>
                        <label>
                            <input type="radio" name="pachana" value="<?php echo $pachana_name; ?>" required>
                            <!-- เพิ่ม required -->
                            <img src="<?php echo $pachana_image; ?>" alt="<?php echo $pachana_name; ?>">
                            <div class="pachana-name"><?php echo $pachana_name; ?></div>
                        </label>
                    <?php } ?>
                </div>

                <!-- ท็อปปิ้ง -->
                <h3>ท็อปปิ้ง</h3>
                <div class="topping-options">
                    <?php while ($topping = $topping_result->fetch_assoc()) {
                        $topping_value = isset($topping['value']) ? htmlspecialchars($topping['value']) : "";
                        $topping_image = isset($topping['image']) ? "pic/item/" . htmlspecialchars($topping['image']) : "pic/default.png";
                        $topping_name = isset($topping['name']) ? htmlspecialchars($topping['name']) : "ไม่ระบุ";
                        $topping_price = isset($topping['price']) ? number_format($topping['price'], 2) : "0.00";
                        ?>
                        <label>
                            <input type="checkbox" name="topping[]"
                                value="<?php echo htmlspecialchars($topping['name']); ?>">
                            <img src="<?php echo $topping_image; ?>" alt="<?php echo $topping_name; ?>">
                            <div class="topping-name"><?php echo $topping_name; ?></div>
                            <div class="addprice">+<?php echo $topping_price; ?> ฿</div>
                        </label>
                    <?php } ?>
                </div>

                <!-- ใส่จำนวนสินค้า -->
                <h3>จำนวน</h3>
                <input type="number" name="count" min="1" value="1" required>


            </div>

            <!-- ปุ่มกด -->
            <div class="button-container-option">
                <button type="button" class="cancel-btn" onclick="window.location.href='menu.php'">ยกเลิก</button>
                <button type="submit" class="confirm-btn">ยืนยัน</button>
            </div>
        </form>
    </section>
</body>

</html>

<?php
$stmt->close(); // ปิด prepared statement
$conn->close(); // ปิดการเชื่อมต่อ
?>