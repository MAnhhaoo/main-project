<?php

function get_all_products($new, $sale, $view, $cateid = 0)
{
    try {
        $conn = connectdb();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM tbl_sanpham WHERE 1";
        if ($sale == 1) {
            $sql .= " AND sale = 1";
        } else {
            $sql .= " AND sale = 0";
        }
        if ($cateid != 0) {
            $sql .= " AND iddm=" . $cateid;
        }

        // View = 1; Ở trang home ( trang chủ ), view = 0 ở trang khác.
        if ($view == 1) {
            $sql .= " LIMIT 4";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}

function loadall_sanpham_timkiem($kyw, $iddm, $price_min, $price_max, $limit, $offset) {
    $sql = "SELECT * FROM tbl_sanpham WHERE don_gia BETWEEN :price_min AND :price_max";
    
    if (!empty($kyw)) {
        $sql .= " AND tensp LIKE :kyw";
    }

    if ($iddm > 0) {
        $sql .= " AND ma_danhmuc = :iddm";
    }

    $sql .= " LIMIT :limit OFFSET :offset"; // Thêm LIMIT và OFFSET để phân trang

    $stmt = connectdb()->prepare($sql);
    $stmt->bindParam(':price_min', $price_min, PDO::PARAM_INT);
    $stmt->bindParam(':price_max', $price_max, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
    if (!empty($kyw)) {
        $stmt->bindValue(':kyw', "%$kyw%", PDO::PARAM_STR);
    }

    if ($iddm > 0) {
        $stmt->bindParam(':iddm', $iddm, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}







function all_sanpham($kyw, $iddm, $price_min, $price_max) {
    $sql = "SELECT COUNT(*) as total FROM tbl_sanpham WHERE 1=1";
    $params = []; // Khởi tạo mảng tham số

    if (!empty($kyw)) {
        $sql .= " AND tensp LIKE ?";
        $params[] = "%$kyw%";
    }
    if ($iddm > 0) {
        $sql .= " AND ma_danhmuc = ?";
        $params[] = $iddm;
    }
    if ($price_min > 0) {
        $sql .= " AND don_gia >= ?";
        $params[] = $price_min;
    }
    if ($price_max < PHP_INT_MAX) {
        $sql .= " AND don_gia <= ?";
        $params[] = $price_max;
    }

    $result = pdo_query($sql, ...$params);
   
    return $result[0]['total'] ?? 0;
}






function get_sale_products($numbers)
{
    try {
        $conn = connectdb();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM tbl_sanpham WHERE 1";
        $sql .= " AND sale = 1";
        // $sql .= " AND LIMIT $numbers";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}

function get_one_product($id)
{
    try {
        $conn = connectdb();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM tbl_sanpham WHERE id=" . $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}

function get_images($proid, $priority = 1)
{
    try {
        $conn = connectdb();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM tbl_hinhanh WHERE douutien = '$priority' and idsp='$proid'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
}

function get_cate_name($cateid)
{
    try {
        $conn = connectdb();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT tendanhmuc FROM tbl_danhmuc WHERE id=" . $cateid;

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function totalRecords($sql)
{
    $conn = connectdb();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $sql = "SELECT * FROM tbl_sanpham WHERE 1";
    // if ($cateid != 0) {
    //     $sql .= " AND ma_danhmuc = '$cateid'";
    // }
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $finalresult = $stmt->fetchAll();
    return count($finalresult);
}