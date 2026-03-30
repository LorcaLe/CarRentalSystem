<?php

require_once __DIR__ . "/../models/Vehicle.php";

class PartnerController {

    private $vehicleModel;

    public function __construct() {
        $this->vehicleModel = new Vehicle();
    }

    public function dashboard() {
        $this->requirePartner();
        $ownerId      = $_SESSION['user']['id'];
        $selectedMonth = (int)($_GET['month'] ?? date('n'));
        $currentYear   = (int)date('Y');

        $myCars  = $this->vehicleModel->getCarsByOwner($ownerId);
        $stats   = $this->vehicleModel->getOwnerStats($ownerId);

        $stats['total_bookings']  = array_sum(array_column($this->getDailyData($ownerId, date('n'), date('Y')), 'bookings'));
        $stats['total_revenue']   = array_sum(array_column($this->getDailyData($ownerId, date('n'), date('Y')), 'revenue'));
        $stats['daily_data']      = $this->getDailyData($ownerId, $selectedMonth, $currentYear);
        $stats['car_revenue']     = $this->getCarRevenue($ownerId, $selectedMonth, $currentYear);

        require_once __DIR__ . "/../views/partner/dashboard.php";
    }

    private function getDailyData($ownerId, $month, $year) {
        $db   = (new Database())->conn;
        $stmt = $db->prepare(
            "SELECT DAY(b.pickup_date) AS day,
                    COUNT(*) AS bookings,
                    SUM(b.total_price) AS revenue
            FROM bookings b
            JOIN vehicles v ON b.vehicle_id = v.id
            WHERE v.owner_id = ?
            AND MONTH(b.pickup_date) = ?
            AND YEAR(b.pickup_date)  = ?
            AND b.status != 'Cancelled'
            GROUP BY DAY(b.pickup_date)
            ORDER BY day"
        );
        $stmt->bind_param("iii", $ownerId, $month, $year);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getCarRevenue($ownerId, $month, $year) {
        $db   = (new Database())->conn;
        $stmt = $db->prepare(
            "SELECT v.id, v.name, v.image, v.status,
                    COUNT(b.id)         AS bookings,
                    COALESCE(SUM(b.total_price), 0) AS revenue
            FROM vehicles v
            LEFT JOIN bookings b ON b.vehicle_id = v.id
                AND MONTH(b.pickup_date) = ?
                AND YEAR(b.pickup_date)  = ?
                AND b.status != 'Cancelled'
            WHERE v.owner_id = ?
            GROUP BY v.id
            ORDER BY revenue DESC"
        );
        $stmt->bind_param("iii", $month, $year, $ownerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function getMonthlyData($ownerId, $type) {
        $db = (new Database())->conn;

        if ($type === 'bookings') {
            $sql = "SELECT MONTH(b.created_at) AS m, COUNT(*) AS val
                    FROM bookings b
                    JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE v.owner_id = ?
                    AND YEAR(b.created_at) = YEAR(NOW())
                    AND b.status != 'Cancelled'
                    GROUP BY m";
        } else {
            $sql = "SELECT MONTH(b.created_at) AS m, SUM(b.total_price) AS val
                    FROM bookings b
                    JOIN vehicles v ON b.vehicle_id = v.id
                    WHERE v.owner_id = ?
                    AND YEAR(b.created_at) = YEAR(NOW())
                    AND b.status = 'Confirmed'
                    GROUP BY m";
        }

        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $rows   = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $result = array_fill(1, 12, 0);
        foreach ($rows as $r) {
            $result[(int)$r['m']] = (int)$r['val'];
        }
        return $result;
    }

    public function myCars() {
        $this->requirePartner();
        $ownerId = $_SESSION['user']['id'];
        $myCars  = $this->vehicleModel->getCarsByOwner($ownerId);
        require_once __DIR__ . "/../views/partner/my_cars.php";
    }

    public function registerCar() {
        $this->requirePartner();
        require_once __DIR__ . "/../views/partner/register_car.php";
    }

    public function submitCar() {
        $this->requirePartner();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $imageName  = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = __DIR__ . "/../../images/" . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $error = "Không thể tải lên hình ảnh xe.";
            require_once __DIR__ . "/../views/partner/register_car.php";
            return;
        }

        $data = [
            'owner_id'     => $_SESSION['user']['id'],
            'name'         => $_POST['name'],
            'branch'       => $_POST['branch'],
            'price'        => (int)$_POST['price_per_day'],
            'seats'        => (int)$_POST['seats'],
            'transmission' => $_POST['transmission'],
            'fuel_type'    => $_POST['fuel_type']  ?? 'Petrol',
            'location'     => $_POST['location']   ?? 'Ho Chi Minh City',
            'image'        => $imageName,
            'status'       => 'Pending',
            'description'  => $_POST['description']  ?? '',
        ];

        if ($this->vehicleModel->register($data)) {
            header("Location: /car_rental/public/partner/my-cars?msg=submitted");
        } else {
            $error = "Lỗi kết nối cơ sở dữ liệu!";
            require_once __DIR__ . "/../views/partner/register_car.php";
        }
    }

    public function deleteCar() {
        $this->requirePartner();
        $id      = (int)($_POST['id'] ?? 0);
        $ownerId = $_SESSION['user']['id'];

        // Chỉ cho phép xóa xe của chính mình
        $car = $this->vehicleModel->getCarById($id);
        if ($car && (int)$car['owner_id'] === $ownerId) {
            $this->vehicleModel->delete($id);
        }

        header("Location: /car_rental/public/partner/my-cars?msg=deleted");
        exit;
    }

    // -------------------------------------------------------------------------
    private function requirePartner() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['partner', 'admin'])) {
            header("Location: /car_rental/public/partner/login");
            exit;
        }
    }
}