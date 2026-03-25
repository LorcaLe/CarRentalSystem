<?php

require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Booking.php';

class BookingController {

    private $vehicleModel;
    private $bookingModel;

    public function __construct() {
        $this->vehicleModel = new Vehicle();
        $this->bookingModel = new Booking();
    }

    // -------------------------------------------------------------------------
    // Hiển thị form đặt xe
    // -------------------------------------------------------------------------

    public function showBookingForm() {
        $this->requireLogin();

        $vehicle_id = (int)($_GET['vehicle_id'] ?? 0);
        $vehicle    = $this->vehicleModel->getCarById($vehicle_id);

        if (!$vehicle) {
            die("Error: Car not found!");
        }

        require_once __DIR__ . '/../views/booking/book.php';
    }

    // -------------------------------------------------------------------------
    // Tính tiền và hiển thị trang xác nhận đơn
    // -------------------------------------------------------------------------

    public function calculateBooking() {
        $this->requireLogin();

        $vehicle_id      = (int)$_POST['vehicle_id'];
        $pickup_date     = $_POST['pickup_date'];
        $return_date     = $_POST['return_date'];
        $pickup_time     = $_POST['pickup_time'];
        $return_time     = $_POST['return_time'];
        $pickup_location = $_POST['pickup_location'];

        // Chặn ngày trong quá khứ
        if ($pickup_date < date('Y-m-d')) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=past_date");
            exit;
        }

        // Kiểm tra xe còn trống không
        $availableCars = $this->vehicleModel->getAvailableVehicles($pickup_date, $return_date, $pickup_location);
        $isAvailable   = false;
        foreach ($availableCars as $car) {
            if ((int)$car['id'] === $vehicle_id) {
                $isAvailable = true;
                break;
            }
        }

        if (!$isAvailable) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=already_booked");
            exit;
        }

        // Tính số ngày và tổng tiền
        $start    = new DateTime($pickup_date . ' ' . $pickup_time);
        $end      = new DateTime($return_date . ' ' . $return_time);
        $days     = max(1, $start->diff($end)->days);
        $vehicle  = $this->vehicleModel->getCarById($vehicle_id);
        $total_price = $days * $vehicle['price_per_day'];

        require_once __DIR__ . '/../views/booking/confirm.php';
    }

    // -------------------------------------------------------------------------
    // Lưu đơn đặt xe vào DB
    // -------------------------------------------------------------------------

    public function confirmBooking() {
        $this->requireLogin();

        $data = [
            'user_id'         => $_SESSION['user']['id'],
            'vehicle_id'      => (int)$_POST['vehicle_id'],
            'pickup_location' => $_POST['pickup_location'],
            'pickup_date'     => $_POST['pickup_date'],
            'pickup_time'     => $_POST['pickup_time'],
            'return_date'     => $_POST['return_date'],
            'return_time'     => $_POST['return_time'],
            'total_price'     => (float)$_POST['total_price'],
            'payment_method'  => $_POST['payment_method'] ?? 'cash',
        ];

        if ($this->bookingModel->create($data)) {
            require_once __DIR__ . "/../views/payment/success.php";
        } else {
            echo "Error saving booking. Please check your database connection.";
        }
    }

    // -------------------------------------------------------------------------
    // Lịch sử đơn của user
    // -------------------------------------------------------------------------

    public function myBookings() {
        $this->requireLogin();

        $bookings = $this->bookingModel->getBookingsByUser($_SESSION['user']['id']);
        require_once __DIR__ . "/../views/booking/my_booking.php";
    }

    // -------------------------------------------------------------------------
    // Hủy đơn
    // -------------------------------------------------------------------------

    public function cancelBooking() {
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($this->bookingModel->updateStatus($id, 'Cancelled')) {
            header("Location: /car_rental/public/booking/my-bookings?msg=cancelled");
        } else {
            echo "Failed to cancel booking.";
        }
    }

    // -------------------------------------------------------------------------
    // API: Trả danh sách khoảng ngày đã bị đặt của một xe
    // Dùng cho datepicker ở front-end để disable ngày không chọn được
    // -------------------------------------------------------------------------

    public function getBookedDates() {
        $vehicle_id = (int)($_GET['vehicle_id'] ?? 0);

        if ($vehicle_id <= 0) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        // Lấy đơn còn hiệu lực (bỏ qua Cancelled)
        $stmt = $this->bookingModel->getActiveBookingDates($vehicle_id);

        $disabledRanges = [];
        foreach ($stmt as $row) {
            $disabledRanges[] = [
                'from' => $row['pickup_date'],
                'to'   => $row['return_date'],
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($disabledRanges);
        exit;
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function requireLogin() {
        if (!isset($_SESSION['user'])) {
            header("Location: /car_rental/public/login");
            exit;
        }
    }
}