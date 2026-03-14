<?php

require_once __DIR__ . "/../models/Vehicle.php";

class VehicleController {

private $vehicleModel;

public function __construct(){

$this->vehicleModel = new Vehicle();

}

/* load homepage */

public function index(){

$cars = $this->vehicleModel->getAllCars();

require __DIR__ . "/../views/home/index.php";

}

/* car detail popup */

public function getCarDetail(){

$id = $_GET['id'];

$car = $this->vehicleModel->getCarById($id);

echo json_encode($car);

}

/* search cars */

public function searchCars(){

$cars = $this->vehicleModel->search($_POST);

echo json_encode($cars);

}

/* filter cars */

public function filterCars(){

$cars = $this->vehicleModel->filter($_POST);

echo json_encode($cars);

}

}