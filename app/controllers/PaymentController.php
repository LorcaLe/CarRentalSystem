<?php
// app/controllers/PaymentController.php

require_once __DIR__ . "/../models/Vehicle.php";
require_once __DIR__ . "/../models/Booking.php";

class PaymentController {
    
    private $vehicleModel;

    public function __construct() {
        $this->vehicleModel = new Vehicle();
    }

    public function index() {
        // Nếu không có dữ liệu POST đẩy tới, trả về trang chủ
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /car_rental/public/");
            exit;
        }

        // Bắt buộc người dùng phải đăng nhập trước khi thanh toán
        if (!isset($_SESSION['user'])) {
            // Bạn có thể lưu tạm dữ liệu vào session ở đây nếu muốn nâng cao
            header("Location: /car_rental/public/login");
            exit;
        }

        // Lấy chi tiết xe từ Database để đảm bảo tính chính xác
        $vehicle_id = $_POST['vehicle_id'];
        $vehicle = $this->vehicleModel->getCarById($vehicle_id);

        if (!$vehicle) {
            die("Error: Vehicle not found.");
        }

        // Truyền dữ liệu sang View
        require __DIR__ . "/../views/payment/checkout.php";
    }
}
?>