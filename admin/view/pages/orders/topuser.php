<?php
// Kết nối database sử dụng PDO
$conn = connectdb();

// Hàm lấy top 5 người mua nhiều nhất theo ngày, tháng, năm


// Lấy giá trị từ ô nhập
$date_start = $_GET['date_start'] ?? null;
$date_end = $_GET['date_end'] ?? null;

// Truy vấn dữ liệu
$sql = "SELECT 
    o.iduser AS customer_id,
    o.name AS customer_name,
    o.dienThoai AS SoDienThoai,
    o.address AS address,
    SUM(od.soluong * od.dongia) AS total_spent
FROM tbl_order o
JOIN tbl_order_detail od ON o.id = od.iddonhang
WHERE o.trangthai = 4";

$conditions = [];
$params = [];

if ($date_start) {
    $conditions[] = "DATE(o.timeorder) >= :date_start";
    $params[':date_start'] = $date_start;
}
if ($date_end) {
    $conditions[] = "DATE(o.timeorder) <= :date_end";
    $params[':date_end'] = $date_end;
}

if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}
$sql .= " GROUP BY o.iduser
          ORDER BY total_spent DESC
          LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$topBuyers = $stmt->fetchAll();
?>

<div>
    <h2 class="text-center">Bảng Xếp Hạng Khách Hàng</h2>
</div>

<form method="GET" action="index.php" class="mb-4">
    <input type="hidden" name="act" value="top_user"> <!-- Giữ nguyên trang -->

    <label for="date_start">Từ ngày:</label>
    <input type="date" id="date_start" name="date_start" value="<?= htmlspecialchars($_GET['date_start'] ?? '') ?>">

    <label for="date_end">Đến ngày:</label>
    <input type="date" id="date_end" name="date_end" value="<?= htmlspecialchars($_GET['date_end'] ?? '') ?>">

    <button type="submit">Tìm Kiếm</button>
</form>

<div class="row">
    <div class="col-12 col-lg-12 d-flex">
        <div class="card w-100">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>ID User</th>
                                <th>Họ Tên</th>
                                <th>Số Điện Thoại</th>
                                <th>Địa Chỉ</th>
                                <th>Tổng Chi Tiêu (VNĐ)</th>
                                <th>Chi Tiết Đơn Hàng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topBuyers)): ?>
                            <?php foreach ($topBuyers as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['customer_id']) ?></td>
                                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                <td><?= htmlspecialchars($row['SoDienThoai']) ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td><?= number_format($row['total_spent'], 0, ',', '.') ?> VNĐ</td>
                                <td>
                                    <form method="GET" action="index.php">
                                    <input type="hidden" name="act" value="order_list_detail">
                                    <input type="hidden" name= "customer_id" value= <?= $row['customer_id'] ?>>
                                    <input type="hidden" name="date_start" value="<?= htmlspecialchars($date_start) ?>">
                                        <input type="hidden" name="date_end" value="<?= htmlspecialchars($date_end) ?>">
                                    <button class="btn btn-primary" > Xem chi tiết</button>
                                    </form>

                                </td>
                            </tr>

                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="7">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>