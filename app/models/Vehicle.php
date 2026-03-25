<?php

require_once __DIR__ . "/../../core/Database.php";

class Vehicle {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // -------------------------------------------------------------------------
    // READ
    // -------------------------------------------------------------------------

    public function getCarById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllCars() {
        $result = $this->conn->query("SELECT * FROM vehicles ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getLuxuryCars($limit = 8) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM vehicles WHERE status = 'Approved'
             ORDER BY price_per_day DESC LIMIT ?"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPopularCars($limit = 8) {
        $stmt = $this->conn->prepare(
            "SELECT v.*, COUNT(b.id) AS booking_count
             FROM vehicles v
             LEFT JOIN bookings b ON v.id = b.vehicle_id
             WHERE v.status = 'Approved'
             GROUP BY v.id
             ORDER BY booking_count DESC
             LIMIT ?"
        );
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCarsPagination($limit, $offset) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM vehicles WHERE status = 'Approved' LIMIT ? OFFSET ?"
        );
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalCarsCount() {
        $result = $this->conn->query(
            "SELECT COUNT(*) AS total FROM vehicles WHERE status = 'Approved'"
        );
        return $result->fetch_assoc()['total'];
    }

    public function getPendingCars() {
        $result = $this->conn->query(
            "SELECT v.*, u.name AS owner_name
             FROM vehicles v
             JOIN users u ON v.owner_id = u.id
             WHERE v.status = 'Pending'"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // -------------------------------------------------------------------------
    // SEARCH (trang /cars với bộ lọc sidebar)
    // -------------------------------------------------------------------------

    public function search($data, $limit, $offset) {
        [$where, $params, $types] = $this->buildFilterClause($data);

        $sql    = "SELECT * FROM vehicles WHERE status = 'Approved'" . $where . " LIMIT ? OFFSET ?";
        $params = array_merge($params, [(int)$limit, (int)$offset]);
        $types .= "ii";

        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countFilteredCars($data) {
        [$where, $params, $types] = $this->buildFilterClause($data);

        $sql  = "SELECT COUNT(*) AS total FROM vehicles WHERE status = 'Approved'" . $where;
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    /**
     * Xây dựng mệnh đề WHERE dùng chung cho search() và countFilteredCars().
     * Trả về [$whereClause, $params, $types].
     */
    private function buildFilterClause($data) {
        $where  = "";
        $params = [];
        $types  = "";

        // Location
        if (!empty($data['location'])) {
            $where   .= " AND location LIKE ?";
            $params[] = "%" . $data['location'] . "%";
            $types   .= "s";
        }

        // Price range (có thể là mảng nhiều khoảng)
        if (!empty($data['price'])) {
            $ranges     = is_array($data['price']) ? $data['price'] : [$data['price']];
            $conditions = [];
            foreach ($ranges as $range) {
                if (strpos($range, '-') !== false) {
                    [$min, $max]  = explode("-", $range);
                    $conditions[] = "price_per_day BETWEEN ? AND ?";
                    $params[]     = (int)$min;
                    $params[]     = (int)$max;
                    $types       .= "ii";
                }
            }
            if (!empty($conditions)) {
                $where .= " AND (" . implode(" OR ", $conditions) . ")";
            }
        }

        // Seats
        if (!empty($data['seats'])) {
            $seats        = (array)$data['seats'];
            $placeholders = implode(",", array_fill(0, count($seats), "?"));
            $where       .= " AND seats IN ($placeholders)";
            foreach ($seats as $s) {
                $params[] = (int)$s;
                $types   .= "i";
            }
        }

        // Brand
        if (!empty($data['brand'])) {
            $brands       = (array)$data['brand'];
            $placeholders = implode(",", array_fill(0, count($brands), "?"));
            $where       .= " AND Branch IN ($placeholders)";
            foreach ($brands as $b) {
                $params[] = $b;
                $types   .= "s";
            }
        }

        // Loại trừ xe đã bị đặt trùng khoảng thời gian
        if (!empty($data['pickup_date']) && !empty($data['return_date'])) {
            $where   .= " AND id NOT IN (
                            SELECT vehicle_id FROM bookings
                            WHERE status != 'Cancelled'
                            AND NOT (return_date < ? OR pickup_date > ?)
                        )";
            $params[] = $data['pickup_date'];
            $params[] = $data['return_date'];
            $types   .= "ss";
        }



        return [$where, $params, $types];
    }

    // -------------------------------------------------------------------------
    // SEARCH BAR (homepage — lọc theo location + ngày không bị trùng lịch)
    // -------------------------------------------------------------------------

    /**
     * Trả về những xe Approved, khớp location, và KHÔNG bị trùng lịch đặt.
     */
    public function searchBar($location, $pickupDate, $returnDate) {
        $sql    = "SELECT * FROM vehicles
                   WHERE status = 'Approved'";
        $params = [];
        $types  = "";

        if (!empty($location)) {
            $sql    .= " AND location LIKE ?";
            $params[] = "%" . $location . "%";
            $types   .= "s";
        }

        // Loại trừ xe đã có đơn đặt trùng khoảng thời gian (không kể đơn Cancelled)
        if (!empty($pickupDate) && !empty($returnDate)) {
            $sql    .= " AND id NOT IN (
                            SELECT vehicle_id FROM bookings
                            WHERE status != 'Cancelled'
                            AND NOT (return_date < ? OR pickup_date > ?)
                        )";
            $params[] = $pickupDate;
            $params[] = $returnDate;
            $types   .= "ss";
        }

        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lấy xe trống theo khoảng thời gian và địa điểm (dùng cho BookingController).
     */
    public function getAvailableVehicles($pickupDate, $returnDate, $location) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM vehicles
             WHERE location LIKE ?
             AND id NOT IN (
                 SELECT vehicle_id FROM bookings
                 WHERE status != 'Cancelled'
                 AND NOT (return_date < ? OR pickup_date > ?)
             )"
        );
        $locParam = "%" . $location . "%";
        $stmt->bind_param("sss", $locParam, $pickupDate, $returnDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // -------------------------------------------------------------------------
    // CREATE / UPDATE / DELETE
    // -------------------------------------------------------------------------

    public function register($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO vehicles
                (owner_id, name, Branch, price_per_day, seats, transmission, fuel_type, location, image, status, available)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)"
        );
        $stmt->bind_param(
            "issiisssss",
            $data['owner_id'],
            $data['name'],
            $data['branch'],
            $data['price'],
            $data['seats'],
            $data['transmission'],
            $data['fuel_type'],
            $data['location'],
            $data['image'],
            $data['status']
        );
        return $stmt->execute();
    }

    public function update($id, $name, $branch, $price, $seats, $transmission, $fuelType, $image = null) {
        if ($image) {
            $stmt = $this->conn->prepare(
                "UPDATE vehicles SET name=?, Branch=?, price_per_day=?, seats=?, transmission=?, fuel_type=?, image=? WHERE id=?"
            );
            $stmt->bind_param("ssiisssi", $name, $branch, $price, $seats, $transmission, $fuelType, $image, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE vehicles SET name=?, Branch=?, price_per_day=?, seats=?, transmission=?, fuel_type=? WHERE id=?"
            );
            $stmt->bind_param("ssiissi", $name, $branch, $price, $seats, $transmission, $fuelType, $id);
        }
        return $stmt->execute();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE vehicles SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function create($name, $branch, $price, $seats, $transmission, $image) {
        $stmt = $this->conn->prepare(
            "INSERT INTO vehicles (name, Branch, price_per_day, seats, transmission, image, available)
             VALUES (?, ?, ?, ?, ?, ?, 1)"
        );
        $stmt->bind_param("ssiiss", $name, $branch, $price, $seats, $transmission, $image);
        return $stmt->execute();
    }
}