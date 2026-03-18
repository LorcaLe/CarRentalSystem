<?php
// app/controllers/AdminController.php

require_once __DIR__ . '/../models/Vehicle.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {
    
    public function __construct() {
        // Bảo vệ toàn bộ Controller: Chỉ Admin mới được truy cập
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: /car_rental/public/login");
            exit;
        }
    }

    /**
     * Dashboard chính: Hiển thị thống kê tổng quan
     */
    public function index() {
        $bookingModel = new Booking();
        $vehicleModel = new Vehicle();
        $userModel    = new User();

        // 1. Kết nối DB
        require_once __DIR__ . "/../../config/database.php";
        $db = (new Database())->conn;

        // 2. Sửa SQL: Đổi booking_date thành pickup_date cho khớp với ảnh bạn gửi
        $sql = "SELECT MONTH(pickup_date) as month, SUM(total_price) as revenue 
                FROM bookings 
                WHERE status = 'Confirmed' 
                GROUP BY MONTH(pickup_date)";

        $result = $db->query($sql);
        $monthlyRevenue = array_fill(1, 12, 0);

        if ($result) {
            while($row = $result->fetch_assoc()) {
                $month = (int)$row['month'];
                if ($month >= 1 && $month <= 12) {
                    $monthlyRevenue[$month] = (float)$row['revenue'];
                }
            }
        }

        // 3. Gom dữ liệu gửi sang View
        $data = [
            'totalBookings'  => $bookingModel->getTotalBookings(),
            'totalRevenue'   => $bookingModel->getTotalRevenue(),
            'totalCars'      => count($vehicleModel->getAllCars()),
            'totalUsers'     => $userModel->countAllUsers(),
            'recentBookings' => $bookingModel->getAllBookings(5),
            'allUsers'       => $userModel->getAllUsers(),
            'monthlyRevenue' => array_values($monthlyRevenue),
            // Thêm dòng này để sidebar hiện số lượng xe đang chờ duyệt
            'totalRequests'  => count($vehicleModel->getPendingCars() ?? [])
        ];

        $uri = '/admin/dashboard'; // Giúp menu Dashboard sáng lên
        require_once __DIR__ . "/../views/admin/dashboard.php";
    }

    /* ==========================================================
       QUẢN LÝ XE (VEHICLES)
       ========================================================== */

    public function manageVehicles() {
        $vehicleModel = new Vehicle();
        $data['vehicles'] = $vehicleModel->getAllCars();
        require_once __DIR__ . "/../views/admin/vehicles.php";
    }

    public function addVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $branch = $_POST['branch'];
            $price = $_POST['price_per_day'];
            $seats = $_POST['seats'];
            $transmission = $_POST['transmission'];
            $fuel_type = $_POST['fuel_type'] ?? 'Petrol'; // Bổ sung fuel_type

            $imageName = time() . '_' . $_FILES['image']['name'];
            $targetDir = __DIR__ . "/../../../images/"; 
            $targetFile = $targetDir . basename($imageName);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $vehicleModel = new Vehicle();
                // Truyền đủ tham số bao gồm fuel_type nếu Model yêu cầu
                $result = $vehicleModel->create($name, $branch, $price, $seats, $transmission, $imageName);
                
                if ($result) {
                    header("Location: /car_rental/public/admin/vehicles?success=1");
                } else {
                    echo "Lỗi Database khi thêm xe!";
                }
            } else {
                echo "Lỗi upload ảnh xe.";
            }
        }
    }

    public function editVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $branch = $_POST['branch'];
            $price = $_POST['price_per_day'];
            $seats = $_POST['seats'];
            $transmission = $_POST['transmission'];
            $fuel_type = $_POST['fuel_type']; 
            
            $imageName = null;
            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../../../images/" . $imageName);
            }

            $vehicleModel = new Vehicle();
            // Đã fix: Truyền đủ các tham số khớp với hàm update trong Model
            if ($vehicleModel->update($id, $name, $branch, $price, $seats, $transmission, $fuel_type, $imageName)) {
                header("Location: /car_rental/public/admin/vehicles?msg=updated");
            } else {
                echo "Cập nhật thất bại!";
            }
        }
    }

    public function deleteVehicle() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $vehicleModel = new Vehicle();
            $car = $vehicleModel->getCarById($id);
            if ($car && $car['image']) {
                $imagePath = __DIR__ . "/../../../images/" . $car['image'];
                if (file_exists($imagePath)) { unlink($imagePath); }
            }

            if ($vehicleModel->delete($id)) {
                header("Location: /car_rental/public/admin/vehicles?msg=deleted");
                exit();
            }
        }
        echo "Lỗi: Không thể xóa xe.";
    }

    /* ==========================================================
       QUẢN LÝ KÝ GỬI (CONSIGNMENT REQUESTS)
       ========================================================== */

    public function manageRequests() {
        $vehicleModel = new Vehicle();
        $pendingCars = $vehicleModel->getPendingCars();
        
        // Sử dụng đường dẫn chuẩn xác
        $viewPath = __DIR__ . "/../views/admin/request.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Lỗi: Không tìm thấy file view tại: " . $viewPath);
        }
    }

    public function approveCar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $vehicleModel = new Vehicle();
            if ($vehicleModel->updateStatus($id, 'Approved')) {
                header("Location: /car_rental/public/admin/requests?msg=approved");
                exit;
            }
        }
        echo "Lỗi khi duyệt xe.";
    }

    public function rejectCar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $vehicleModel = new Vehicle();
            // Chuyển status thành Rejected
            if ($vehicleModel->updateStatus($id, 'Rejected')) {
                header("Location: /car_rental/public/admin/requests?msg=rejected");
                exit;
            }
        }
        echo "Lỗi khi từ chối xe.";
    }

    /* ==========================================================
       QUẢN LÝ ĐƠN HÀNG (BOOKINGS)
       ========================================================== */

    public function manageBookings() {
        $bookingModel = new Booking();
        $data['allBookings'] = $bookingModel->getAllBookings();
        require_once __DIR__ . "/../views/admin/bookings.php";
    }

    public function confirmBooking() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new Booking())->updateStatus($id, 'Confirmed');
        }
        header("Location: /car_rental/public/admin/bookings");
    }

    public function cancelBooking() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            (new Booking())->updateStatus($id, 'Cancelled');
        }
        header("Location: /car_rental/public/admin/bookings");
    }

    /* ==========================================================
       QUẢN LÝ NGƯỜI DÙNG (CUSTOMERS)
       ========================================================== */

    public function manageCustomers() {
        $userModel = new User();
        $data['customers'] = $userModel->getAllUsers();
        require_once __DIR__ . "/../views/admin/customers.php";
    }

    public function updateRole() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_POST['user_id'];
            $newRole = $_POST['role'];
            $userModel = new User();
            if ($userModel->updateUserRole($userId, $newRole)) {
                header("Location: /car_rental/public/admin/customers?msg=role_updated");
            }
        }
    }

    /* ==========================================================
       Support
       ========================================================== */
    
    // app/controllers/AdminController.php

    public function manageEnquiries() {
        require_once __DIR__ . "/../../config/database.php";
        $db = (new Database())->conn;

        // Lấy tất cả tin nhắn kèm tên người gửi
        $sql = "SELECT e.*, u.name as user_name FROM enquiries e 
                JOIN users u ON e.user_id = u.id 
                ORDER BY e.created_at DESC";
        $result = $db->query($sql);
        $enquiries = $result->fetch_all(MYSQLI_ASSOC);

        require_once __DIR__ . "/../views/admin/enquiries.php";
    }

    public function replyEnquiry() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $reply = $_POST['reply'];

            require_once __DIR__ . "/../../config/database.php";
            $db = (new Database())->conn;

            $sql = "UPDATE enquiries SET reply = ?, status = 'Replied' WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("si", $reply, $id);

            if ($stmt->execute()) {
                header("Location: /car_rental/public/admin/enquiries?msg=replied");
            }
        }
    }
}