<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
    <style>
    .container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 1000px;
        text-align: center;
    }

    h2 {
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .back-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .back-btn:hover {
        background-color: #218838;
    }

    .pagination {
        margin-top: 15px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 12px;
        margin: 2px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .pagination a:hover {
        background-color: #0056b3;
    }

    .pagination .active {
        background-color: #0056b3;
    }

    .pagination .disabled {
        background-color: #ccc;
        pointer-events: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Chi Tiết Đơn Hàng</h2>

        <?php
        $conn = connectdb();
        $customer_id = $_GET['customer_id'];
        $date_start = $_GET['date_start'] ?? null;
        $date_end = $_GET['date_end'] ?? null;
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Truy vấn danh sách đơn hàng
        $sql = "SELECT iduser, madonhang, timeorder, tongdonhang , id , dienThoai, address, name
                FROM tbl_order 
                WHERE iduser = ? AND trangthai = 4";
        $params = [$customer_id];
        $conditions = [];

        if ($date_start) {
            $conditions[] = "DATE(timeorder) >= ?";
            $params[] = $date_start;
        }
        if ($date_end) {
            $conditions[] = "DATE(timeorder) <= ?";
            $params[] = $date_end;
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY timeorder DESC LIMIT $limit OFFSET $offset";

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        // Truy vấn tổng số đơn hàng
        $sql_total = "SELECT COUNT(*) 
                      FROM tbl_order 
                      WHERE iduser = ? AND trangthai = 4";
        $params_total = [$customer_id];
        $conditions_total = [];

        if ($date_start) {
            $conditions_total[] = "DATE(timeorder) >= ?";
            $params_total[] = $date_start;
        }
        if ($date_end) {
            $conditions_total[] = "DATE(timeorder) <= ?";
            $params_total[] = $date_end;
        }

        if (!empty($conditions_total)) {
            $sql_total .= " AND " . implode(" AND ", $conditions_total);
        }

        $stmt = $conn->prepare($sql_total);
        $stmt->execute($params_total);
        $total_orders = $stmt->fetchColumn();
        $total_pages = ceil($total_orders / $limit);

        // Giới hạn số trang hiển thị
        $max_pages_to_show = 5;
        $half_pages = floor($max_pages_to_show / 2);
        $start_page = max(1, $page - $half_pages);
        $end_page = min($total_pages, $page + $half_pages);

        if ($end_page - $start_page + 1 < $max_pages_to_show) {
            if ($start_page == 1) {
                $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);
            } else {
                $start_page = max(1, $end_page - $max_pages_to_show + 1);
            }
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Ngày Đặt</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Tổng Tiền (VNĐ)</th>
                    <th>Chi tiết đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['madonhang']) ?></td>
                    <td><?= htmlspecialchars($order['timeorder']) ?></td>
                    <td><?= htmlspecialchars($order['name']) ?></td>
                    <td><?= htmlspecialchars($order['dienThoai']) ?></td>
                    <td><?= number_format($order['tongdonhang'], 0, ',', '.') ?> VNĐ</td>
                    <td>
                        <a href="./index.php?act=orderdetail&iddh=<?= htmlspecialchars($order['id']) ?>"
                            class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                            data-bs-original-title="View detail" aria-label="Views">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3">Không có đơn hàng</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <!-- Nút Trang trước -->
            <?php if ($page > 1): ?>
            <a
                href="?act=order_list_detail&customer_id=<?= htmlspecialchars($customer_id) ?>&date_start=<?= htmlspecialchars($date_start) ?>&date_end=<?= htmlspecialchars($date_end) ?>&page=<?= $page - 1 ?>">«
                Trước</a>
            <?php else: ?>
            <a href="#" class="disabled">« Trước</a>
            <?php endif; ?>

            <!-- Các số trang -->
            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
            <a href="?act=order_list_detail&customer_id=<?= htmlspecialchars($customer_id) ?>&date_start=<?= htmlspecialchars($date_start) ?>&date_end=<?= htmlspecialchars($date_end) ?>&page=<?= $i ?>"
                <?= ($i == $page) ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <!-- Nút Trang sau -->
            <?php if ($page < $total_pages): ?>
            <a
                href="?act=order_list_detail&customer_id=<?= htmlspecialchars($customer_id) ?>&date_start=<?= htmlspecialchars($date_start) ?>&date_end=<?= htmlspecialchars($date_end) ?>&page=<?= $page + 1 ?>">Sau
                »</a>
            <?php else: ?>
            <a href="#" class="disabled">Sau »</a>
            <?php endif; ?>
        </div>

        <a href="index.php?act=top_user" class="back-btn">⬅ Quay Lại</a>
    </div>
</body>

</html>