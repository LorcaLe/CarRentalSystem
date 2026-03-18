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

    /**
     * Xác nhận đơn hàng và lưu vào Database
     */
    public function confirmBooking() {
        if (!isset($_SESSION['user'])) {
            header("Location: /car_rental/public/login");
            exit;
        }

        // Chú ý: Trạng thái mặc định nên là 'Pending' để Admin duyệt
        // Nếu bạn muốn tự động Confirm luôn thì để 'Confirmed'
        $data = [
            'user_id'         => $_SESSION['user']['id'],
            'vehicle_id'      => $_POST['vehicle_id'],
            'pickup_location' => $_POST['pickup_location'],
            'pickup_date'     => $_POST['pickup_date'],
            'pickup_time'     => $_POST['pickup_time'],
            'return_date'     => $_POST['return_date'],
            'return_time'     => $_POST['return_time'],
            'total_price'     => $_POST['total_price'],
            'payment_method'  => $_POST['payment_method'] ?? 'cash'
        ];

        if ($this->bookingModel->create($data)) {
            require_once __DIR__ . "/../views/payment/success.php";
        } else {
            echo "Error saving booking. Please check your database connection.";
        }
    }

    /**
     * Tính toán số ngày và tổng tiền trước khi chốt đơn
     */
    public function calculateBooking() {
            $vehicle_id = $_POST['vehicle_id'];
            $pickup_date = $_POST['pickup_date'];
            $return_date = $_POST['return_date'];
            $pickup_location = $_POST['pickup_location'];

            // 1. Chặn ngày quá khứ (Back-end Validation)
            $today = date('Y-m-d');
            if ($pickup_date < $today) {
                header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=past_date");
                exit;
            }

            // 2. Kiểm tra xem xe có bị trùng lịch không (Available Check)
            // Sử dụng hàm getAvailableVehicles đã viết ở Model Vehicle
            $availableCars = $this->vehicleModel->getAvailableVehicles($pickup_date, $return_date, $pickup_location);
            
            // Kiểm tra xem ID của xe khách chọn có nằm trong danh sách xe TRỐNG không
            $isAvailable = false;
            foreach ($availableCars as $car) {
                if ($car['id'] == $vehicle_id) {
                    $isAvailable = true;
                    break;
                }
            }

            if (!$isAvailable) {
                // Nếu xe đã bị đặt, quay lại trang trước và báo lỗi
                header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=already_booked");
                exit;
            }

            // 3. Nếu mọi thứ OK, tính tiền như bình thường
            $pickup_dt = $pickup_date . ' ' . $_POST['pickup_time'];
            $return_dt = $return_date . ' ' . $_POST['return_time'];
            
            $start = new DateTime($pickup_dt);
            $end = new DateTime($return_dt);
            $interval = $start->diff($end);
            $days = $interval->days ?: 1;

            $vehicle = $this->vehicleModel->getCarById($vehicle_id);
            $total_price = $days * $vehicle['price_per_day'];

            require_once __DIR__ . '/../views/booking/confirm.php';
        }

    /**
     * Xem danh sách đơn hàng của tôi (Khách hàng)
     */
    public function myBookings() {
        if (!isset($_SESSION['user'])) {
            header("Location: /car_rental/public/login");
            exit;
        }

        // PHẢI lấy ['id'] từ mảng session
        $user_id = $_SESSION['user']['id'];
        
        $bookings = $this->bookingModel->getBookingsByUser($user_id);
        require_once __DIR__ . "/../views/booking/my_booking.php";
    }

    /**
     * Hiển thị Form điền thông tin thuê xe
     */
    public function showBookingForm() {
        if (!isset($_SESSION['user'])) {
            header("Location: /car_rental/public/login");
            exit;
        }

        $vehicle_id = $_GET['vehicle_id'] ?? die("Error: Vehicle ID is missing!");
        $vehicle = $this->vehicleModel->getCarById($vehicle_id);

        if (!$vehicle) {
            die("Error: Car not found!");
        }

        require_once __DIR__ . '/../views/booking/book.php';
    }

    /**
     * Khách hàng tự hủy đơn hàng
     */
    public function cancelBooking() {
        // Hỗ trợ cả POST và GET cho linh hoạt
        $id = $_POST['id'] ?? $_GET['id'];
        
        if($this->bookingModel->updateStatus($id, 'Cancelled')) {
            // Sau khi hủy, quay lại trang lịch sử đơn hàng
            header("Location: /car_rental/public/booking/my-bookings?msg=cancelled");
        } else {
            echo "Failed to cancel booking.";
        }
    }
}