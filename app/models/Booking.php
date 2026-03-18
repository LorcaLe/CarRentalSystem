<?php
// app/models/Booking.php
require_once __DIR__ . "/../../config/database.php";


class Booking {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn; 
        
        if ($this->db === null) {
            die("Database connection failed in Booking Model.");
        }
    }

    /**
     * Hàm lưu đơn hàng mới (Dành cho khách hàng)
     */
    public function create($data) {
        $sql = "INSERT INTO bookings (
                    user_id, vehicle_id, pickup_location, 
                    pickup_date, pickup_time, return_date, 
                    return_time, total_price, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed', NOW())"; // Đổi ở đây thành Confirmed
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iisssssd", 
            $data['user_id'], $data['vehicle_id'], $data['pickup_location'],
            $data['pickup_date'], $data['pickup_time'], $data['return_date'],
            $data['return_time'], $data['total_price']
        );
        return $stmt->execute();
    }

    /**
     * Hàm lấy danh sách đơn hàng cho Admin
     */
    public function getAllBookings($limit = null) {
        $sql = "SELECT b.*, u.name as customer_name, v.name as car_name 
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN vehicles v ON b.vehicle_id = v.id
                ORDER BY b.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $result = $this->db->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Lấy lịch sử đặt xe của 1 khách hàng cụ thể
     */
    public function getBookingsByUser($user_id) {
        $sql = "SELECT b.*, v.name as car_name, v.image as car_image 
                FROM bookings b
                JOIN vehicles v ON b.vehicle_id = v.id
                WHERE b.user_id = ? 
                ORDER BY b.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Tính tổng doanh thu (Chỉ tính những đơn đã Confirmed)
     */
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_price) as total FROM bookings WHERE status = 'Confirmed'";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    /**
     * Đếm tổng số lượng đơn hàng
     */
    public function getTotalBookings() {
        $sql = "SELECT COUNT(*) as total FROM bookings";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    /**
     * Cập nhật trạng thái đơn hàng (Confirm/Cancel)
     */
    public function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function isVehicleAvailable($vehicle_id, $pickup_date, $return_date) {
        $sql = "SELECT COUNT(*) as count FROM bookings 
                WHERE vehicle_id = ? 
                AND status != 'Cancelled' 
                AND NOT (return_date < ? OR pickup_date > ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $vehicle_id, $pickup_date, $return_date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        // Nếu count > 0 nghĩa là có ít nhất 1 đơn đặt trùng lịch
        return $result['count'] == 0;
    }
}

