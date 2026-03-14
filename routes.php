<?php

require_once "app/controllers/AuthController.php";
require_once "app/controllers/VehicleController.php";
require_once "app/controllers/BookingController.php";
require_once "app/controllers/SupportController.php";

$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);

$uri = str_replace("/car_rental/public","",$uri);

switch($uri){

case "/":
    (new VehicleController())->index();
break;

case "/login":

(new AuthController())->login();

break;

case "/register":
    (new AuthController())->register();
break;

case "/logout":

(new AuthController())->logout();

break;

case "/forgot-password":
require "app/views/auth/forgot-password.php";
break;

case "/send-otp":
(new AuthController())->forgotPassword();
break;

case "/verify-otp":
(new AuthController())->verifyOTP();
break;

case "/reset-password":
(new AuthController())->resetPassword();
break;
case "/car-detail":
(new VehicleController())->getCarDetail();
break;

case "/profile":
(new AuthController())->profile();
break;

case "/update-profile":
(new AuthController())->updateProfile();
break;

case "/change-password":
(new AuthController())->changePassword();
break;

case "/search-cars":
(new VehicleController())->searchCars();
break;

case "/filter-cars":
(new VehicleController())->filterCars();
break;

default:
    echo "404 page not found";

}