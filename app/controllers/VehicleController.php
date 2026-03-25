<?php

require_once __DIR__ . "/../models/Vehicle.php";

class VehicleController {

    private $vehicleModel;

    public function __construct() {
        $this->vehicleModel = new Vehicle();
    }

    // -------------------------------------------------------------------------
    // Trang /cars — hiển thị danh sách xe có phân trang + bộ lọc sidebar
    // -------------------------------------------------------------------------

    public function index() {
        $filters = $_GET;
        $limit   = 12;
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $offset  = ($page - 1) * $limit;

        $cars          = $this->vehicleModel->search($filters, $limit, $offset);
        $totalFiltered = $this->vehicleModel->countFilteredCars($filters);
        $totalPages    = (int)ceil($totalFiltered / $limit);

        // Trả JSON khi gọi AJAX (filter sidebar hoặc phân trang)
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'cars'        => $cars,
                'totalPages'  => $totalPages,
                'currentPage' => $page,
            ]);
            exit;
        }

        $luxuryCars  = $this->vehicleModel->getLuxuryCars(8);
        $popularCars = $this->vehicleModel->getPopularCars(8);

        require_once __DIR__ . "/../views/home/index.php";
    }

    // -------------------------------------------------------------------------
    // Endpoint AJAX cho bộ lọc sidebar (POST)
    // -------------------------------------------------------------------------

    public function searchCars() {
        $filters = $_POST;
        $limit   = 12;
        $page    = max(1, (int)($filters['page'] ?? 1));
        $offset  = ($page - 1) * $limit;

        $cars          = $this->vehicleModel->search($filters, $limit, $offset);
        $totalFiltered = $this->vehicleModel->countFilteredCars($filters);
        $totalPages    = (int)ceil($totalFiltered / $limit);

        header('Content-Type: application/json');
        echo json_encode([
            'cars'        => $cars,
            'totalPages'  => $totalPages,
            'currentPage' => $page,
        ]);
        exit;
    }

    // -------------------------------------------------------------------------
    // Search bar trang chủ — lọc theo location + ngày không bị trùng lịch
    // -------------------------------------------------------------------------

    public function searchBar() {
        $location   = trim($_POST['location']    ?? '');
        $pickupDate = trim($_POST['pickup_date']  ?? '');
        $returnDate = trim($_POST['return_date']  ?? '');

        // Validate: ngày pickup phải <= return
        if (!empty($pickupDate) && !empty($returnDate) && $pickupDate > $returnDate) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Ngày trả phải sau ngày nhận xe.']);
            exit;
        }

        $cars = $this->vehicleModel->searchBar($location, $pickupDate, $returnDate);

        header('Content-Type: application/json');
        echo json_encode([
            'status'      => 'success',
            'cars'        => $cars,
            'pickup_date' => $pickupDate,
            'return_date' => $returnDate,
        ]);
        exit;
    }

    // -------------------------------------------------------------------------
    // Chi tiết xe qua AJAX
    // -------------------------------------------------------------------------

    public function getCarDetail() {
        $id  = (int)($_GET['id'] ?? 0);
        $car = $this->vehicleModel->getCarById($id);

        header('Content-Type: application/json');
        echo json_encode($car);
        exit;
    }

    // -------------------------------------------------------------------------
    // Ký gửi xe (User)
    // -------------------------------------------------------------------------

    public function showConsignmentForm() {
        $this->requireLogin();
        require_once __DIR__ . "/../views/vehicle/register.php";
    }

    public function submitConsignment() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $imageName  = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = __DIR__ . "/../../images/" . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $error = "Không thể tải lên hình ảnh xe.";
            require_once __DIR__ . "/../views/vehicle/register.php";
            return;
        }

        $data = [
            'owner_id'     => $_SESSION['user']['id'],
            'name'         => $_POST['name'],
            'branch'       => $_POST['branch'],
            'price'        => (int)$_POST['price_per_day'],
            'seats'        => (int)$_POST['seats'],
            'transmission' => $_POST['transmission'],
            'fuel_type'    => $_POST['fuel_type']    ?? 'Petrol',
            'location'     => $_POST['location']     ?? 'Ho Chi Minh City',
            'image'        => $imageName,
            'status'       => 'Pending',
        ];

        if ($this->vehicleModel->register($data)) {
            require_once __DIR__ . "/../views/vehicle/consignment_success.php";
        } else {
            $error = "Lỗi kết nối cơ sở dữ liệu!";
            require_once __DIR__ . "/../views/vehicle/register.php";
        }
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