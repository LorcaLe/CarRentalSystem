<?php

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

    // -------------------------------------------------------------------------
    // CREATE
    // -------------------------------------------------------------------------

    public function create($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO bookings
                (user_id, vehicle_id, pickup_location, pickup_date, pickup_time,
                 return_date, return_time, total_price, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed', NOW())"
        );
        $stmt->bind_param(
            "iisssssd",
            $data['user_id'],
            $data['vehicle_id'],
            $data['pickup_location'],
            $data['pickup_date'],
            $data['pickup_time'],
            $data['return_date'],
            $data['return_time'],
            $data['total_price']
        );
        return $stmt->execute();
    }

    // -------------------------------------------------------------------------
    // READ
    // -------------------------------------------------------------------------

    public function getAllBookings($limit = null) {
        $sql = "SELECT b.*, u.name AS customer_name, v.name AS car_name
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN vehicles v ON b.vehicle_id = v.id
                ORDER BY b.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getBookingsByUser($user_id) {
        $stmt = $this->db->prepare(
            "SELECT b.*, v.name AS car_name, v.image AS car_image
             FROM bookings b
             JOIN vehicles v ON b.vehicle_id = v.id
             WHERE b.user_id = ?
             ORDER BY b.created_at DESC"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Trả về các khoảng ngày đã bị đặt (không kể Cancelled) của một xe.
     * Dùng cho API getBookedDates() ở BookingController → datepicker front-end.
     */
    public function getActiveBookingDates($vehicle_id) {
        $stmt = $this->db->prepare(
            "SELECT pickup_date, return_date
             FROM bookings
             WHERE vehicle_id = ?
             AND status NOT IN ('Cancelled')"
        );
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // -------------------------------------------------------------------------
    // STATS (dùng cho Admin dashboard)
    // -------------------------------------------------------------------------

    public function getTotalRevenue() {
        $result = $this->db->query(
            "SELECT SUM(total_price) AS total FROM bookings WHERE status = 'Confirmed'"
        );
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function getTotalBookings() {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM bookings");
        return $result->fetch_assoc()['total'] ?? 0;
    }

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    // -------------------------------------------------------------------------
    // AVAILABILITY CHECK
    // -------------------------------------------------------------------------

    /**
     * Trả về true nếu xe chưa có đơn nào trùng khoảng thời gian.
     */
    public function isVehicleAvailable($vehicle_id, $pickup_date, $return_date) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS count FROM bookings
             WHERE vehicle_id = ?
             AND status != 'Cancelled'
             AND NOT (return_date < ? OR pickup_date > ?)"
        );
        $stmt->bind_param("iss", $vehicle_id, $pickup_date, $return_date);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['count'] == 0;
    }
}