<?php

require_once __DIR__ . "/../../core/Database.php";

class Vehicle {
    private $conn;

    public function __construct(){
        $db = new Database();
        $this->conn = $db->conn;
    }

    public function getCarById($id){
        $sql = "SELECT * FROM vehicles WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllCars(){
        $sql = "SELECT * FROM vehicles ORDER BY id DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function search($data){
        // Lưu ý: Tên cột trong DB của bạn là "Branch" (Viết hoa chữ B)
        $sql = "SELECT * FROM vehicles WHERE 1=1";
        $params = [];
        $types = "";

        // Filter: Location
        if(!empty($data['location'])){
            $sql .= " AND location LIKE ?";
            $params[] = "%" . $data['location'] . "%";
            $types .= "s";
        }

        // Filter: Price Range
        if(!empty($data['price'])){
            $range = is_array($data['price']) ? $data['price'][0] : $data['price'];
            if (strpos($range, '-') !== false) {
                list($min, $max) = explode("-", $range);
                $sql .= " AND price_per_day BETWEEN ? AND ?";
                $params[] = $min;
                $params[] = $max;
                $types .= "ii";
            }
        }

        // Filter: Seats
        if(!empty($data['seats'])){
            $seats = (array)$data['seats'];
            $placeholders = implode(",", array_fill(0, count($seats), "?"));
            $sql .= " AND seats IN ($placeholders)";
            foreach($seats as $s){
                $params[] = (int)$s;
                $types .= "i";
            }
        }

        // Filter: Brand (Database của bạn dùng 'Branch')
        if(!empty($data['brand'])){
            $brands = (array)$data['brand'];
            $placeholders = implode(",", array_fill(0, count($brands), "?"));
            $sql .= " AND Branch IN ($placeholders)"; // Đã sửa thành Branch theo DB của bạn
            foreach($brands as $b){
                $params[] = $b;
                $types .= "s";
            }
        }

        $stmt = $this->conn->prepare($sql);
        if(!empty($params)){
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Hàm quan trọng: Kiểm tra xe trống theo thời gian
    public function getAvailableVehicles($pickup_dt, $return_dt, $location) {
            $sql = "SELECT * FROM vehicles 
                    WHERE location LIKE ? 
                    AND id NOT IN (
                        SELECT vehicle_id FROM bookings 
                        WHERE status != 'Cancelled' 
                        AND NOT (return_date < ? OR pickup_date > ?)
                    )";
            
            $stmt = $this->conn->prepare($sql); // Đã sửa lỗi ở đây
            $locParam = "%" . $location . "%";
            $stmt->bind_param("sss", $locParam, $pickup_dt, $return_dt);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }

    /**
     * Chức năng: Thêm xe mới (Dùng cho Admin)
     */
    public function create($name, $branch, $price, $seats, $transmission, $image) {
        // Khớp chính xác với các cột trong ảnh phpMyAdmin bạn gửi
        $sql = "INSERT INTO vehicles (name, Branch, price_per_day, seats, transmission, image, available) 
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiiss", $name, $branch, $price, $seats, $transmission, $image);
        return $stmt->execute();
    }

    /**
     * Chức năng: Xóa xe (Dùng cho Admin)
     */
    public function delete($id) {
        $sql = "DELETE FROM vehicles WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // app/models/Vehicle.php

    public function update($id, $name, $branch, $price, $seats, $transmission, $fuel_type, $image = null) {
        if ($image) {
            $sql = "UPDATE vehicles SET name=?, Branch=?, price_per_day=?, seats=?, transmission=?, fuel_type=?, image=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssiisssi", $name, $branch, $price, $seats, $transmission, $fuel_type, $image, $id);
        } else {
            $sql = "UPDATE vehicles SET name=?, Branch=?, price_per_day=?, seats=?, transmission=?, fuel_type=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssiissi", $name, $branch, $price, $seats, $transmission, $fuel_type, $id);
        }
        return $stmt->execute();
    }

    public function register($data) {
        // 1. Thêm location và fuel_type vào danh sách cột và giá trị VALUES
        $sql = "INSERT INTO vehicles (
                    owner_id, name, Branch, price_per_day, 
                    seats, transmission, fuel_type, location, 
                    image, status, available
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
                
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Lỗi Prepare: " . $this->conn->error);
        }

        // 2. Cập nhật bind_param: 
        // Thêm 2 chữ 's' (String) cho fuel_type và location. 
        // Tổng cộng: i (owner_id), s (name), s (branch), i (price), i (seats), s (transmission), s (fuel_type), s (location), s (image), s (status)
        // Chuỗi bind: "issiisssss"
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

    public function getPendingCars() {
        $sql = "SELECT v.*, u.name as owner_name FROM vehicles v 
                JOIN users u ON v.owner_id = u.id 
                WHERE v.status = 'Pending'";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE vehicles SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}