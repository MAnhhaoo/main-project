<?php
// Kết nối database sử dụng PDO
$conn = connectdb();

// Hàm lấy top 5 người mua nhiều nhất theo ngày, tháng, năm


// Lấy giá trị từ ô nhập
$date_start = $_GET['date_start'] ?? null;
$date_end = $_GET['date_end'] ?? null;

// Truy vấn dữ liệu
$sql = "SELECT od.iduser, od.name AS name, od.dienThoai AS phone, od.address, SUM(od.tongdonhang) AS total_spent
        FROM tbl_order od";

$conditions = [];
$params = [];

if ($date_start) {
    $conditions[] = "DATE(od.timeorder) >= :date_start";
    $params[':date_start'] = $date_start;
}
if ($date_end) {
    $conditions[] = "DATE(od.timeorder) <= :date_end";
    $params[':date_end'] = $date_end;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY od.iduser
          ORDER BY total_spent DESC
          LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$topBuyers = $stmt->fetchAll();
?>

<div>
    <h2 class="text-center">📊 Bảng Xếp Hạng Khách Hàng</h2>
</div>

<form method="GET" action="index.php" class="mb-4">
    <input type="hidden" name="act" value="top_user">  <!-- Giữ nguyên trang -->
    
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($topBuyers)): ?>
                                <?php foreach ($topBuyers as $index => $row): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($row['iduser']) ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['phone']) ?></td>
                                        <td><?= htmlspecialchars($row['address']) ?></td>
                                        <td><?= number_format($row['total_spent'], 0, ',', '.') ?> VNĐ</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6">Không có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
