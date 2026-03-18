<?php

require_once __DIR__ . "/../models/Vehicle.php";

class VehicleController {

    private $vehicleModel;

    public function __construct(){
        $this->vehicleModel = new Vehicle();
    }

    /* Load trang chủ */
    public function index(){
        $cars = $this->vehicleModel->getAllCars();
        require __DIR__ . "/../views/home/index.php";
    }

    /* Lấy chi tiết xe qua AJAX */
    public function getCarDetail(){
        $id = $_GET['id'] ?? null;
        $car = $this->vehicleModel->getCarById($id);
        echo json_encode($car);
    }

    /* Tìm kiếm xe */
    public function searchCars(){
        $cars = $this->vehicleModel->search($_POST);
        echo json_encode($cars);
    }

    /**
     * Hiển thị trang Form ký gửi xe cho User
     */
    public function showConsignmentForm() {
        if (!isset($_SESSION['user'])) {
            header("Location: /car_rental/public/login");
            exit;
        }
        require_once __DIR__ . "/../views/vehicle/register.php";
    }

    /**
     * Xử lý gửi yêu cầu ký gửi xe
     */
    public function submitConsignment() {
        if (!isset($_SESSION['user'])) {
            header("Location: /car_rental/public/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Xử lý Upload Ảnh
            $imageName = time() . '_' . $_FILES['image']['name'];
            $targetPath = __DIR__ . "/../../images/" . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $data = [
                    'owner_id'     => $_SESSION['user']['id'],
                    'name'         => $_POST['name'],
                    'branch'       => $_POST['branch'],
                    'price'        => $_POST['price_per_day'],
                    'seats'        => $_POST['seats'],
                    'transmission' => $_POST['transmission'],
                    'fuel_type'    => $_POST['fuel_type'] ?? 'Petrol',
                    'location'     => $_POST['location'] ?? 'Ho Chi Minh City',
                    'image'        => $imageName,
                    'status'       => 'Pending'
                ];

                if ($this->vehicleModel->register($data)) {
                    // THÔNG BÁO THÀNH CÔNG NGAY TẠI ĐÂY
                    $message = "Yêu cầu ký gửi xe của bạn đã được gửi thành công!";
                    // Bạn có thể require một file view thông báo hoặc hiển thị trong layout hiện tại
                    require_once __DIR__ . "/../views/vehicle/consignment_success.php";
                    exit;
                } else {
                    $error = "Lỗi kết nối cơ sở dữ liệu!";
                }
            } else {
                $error = "Không thể tải lên hình ảnh xe.";
            }
        }
    }
}