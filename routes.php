<?php

session_start();

require_once "app/controllers/AuthController.php";
require_once "app/controllers/VehicleController.php";
require_once "app/controllers/BookingController.php";
require_once "app/controllers/SupportController.php";
require_once "app/controllers/PaymentController.php";
require_once "app/controllers/AdminController.php";
require_once "app/controllers/StaffController.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace("/car_rental/public", "", $uri);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {

    // =========================================================================
    // VEHICLE
    // =========================================================================

    case "/":
        (new VehicleController())->index();
        break;

    case "/car-detail":
        (new VehicleController())->getCarDetail();
        break;

    case "/search-cars":
        (new VehicleController())->searchCars();
        break;

    case "/search-bar":
        (new VehicleController())->searchBar();
        break;

    // =========================================================================
    // AUTH
    // =========================================================================

    case "/login":
        $method === 'POST'
            ? (new AuthController())->handleLogin()
            : (new AuthController())->login();
        break;

    case "/register":
        $method === 'POST'
            ? (new AuthController())->handleRegister()
            : (new AuthController())->register();
        break;

    case "/logout":
        (new AuthController())->logout();
        break;

    case "/profile":
        (new AuthController())->showProfilePage();
        break;

    case "/update-profile":
        if ($method === 'POST') (new AuthController())->updateProfile();
        break;

    case "/update-password":
        if ($method === 'POST') (new AuthController())->updatePassword();
        break;

    case "/update-payment":
        if ($method === 'POST') (new AuthController())->savePayment();
        break;

    case "/verify-current-password":
        if ($method === 'POST') (new AuthController())->verifyCurrentPassword();
        break;

    case "/forgot-password":
        require "app/views/auth/forgot-password.php";
        break;

    case "/send-otp":
        (new AuthController())->forgotPassword();
        break;

    case "/profile/send-otp":
        if ($method === 'POST') (new AuthController())->forgotPassword();
        break;

    case "/verify-otp":
        (new AuthController())->verifyOTP();
        break;

    case "/reset-password":
        (new AuthController())->resetPassword();
        break;

    // =========================================================================
    // BOOKING
    // =========================================================================

    case "/booking-form":
        (new BookingController())->showBookingForm();
        break;

    case "/book-car":
        (new BookingController())->calculateBooking();
        break;

    case "/checkout":
        (new PaymentController())->index();
        break;

    case "/confirm-booking":
        (new BookingController())->confirmBooking();
        break;

    case "/my_booking":
        (new BookingController())->myBookings();
        break;

    case "/cancel-booking":
        (new BookingController())->cancelBooking();
        break;

    // Trả về các khoảng ngày đã bị đặt của một xe (dùng cho datepicker)
    case "/booked-dates":
    case "/get-booked-dates":
        (new BookingController())->getBookedDates();
        break;

    // =========================================================================
    // CONSIGNMENT (Ký gửi xe)
    // =========================================================================

    case "/register-car":
        (new VehicleController())->showConsignmentForm();
        break;

    case "/submit-consignment":
        (new VehicleController())->submitConsignment();
        break;

    // =========================================================================
    // SUPPORT
    // =========================================================================

    case "/enquiry":
        (new SupportController())->index();
        break;

    case "/send-enquiry":
        (new SupportController())->sendEnquiry();
        break;

    case "/get-enquiries":
        (new SupportController())->getEnquiries();
        break;

    case "/ticket/create":
        (new SupportController())->createTicket();
        break;

    case "/ticket/close":
        (new SupportController())->endTicket();
        break;

    case "/ticket/view":
        (new SupportController())->viewDetail();
        break;

    case "/ticket/reply":
        (new SupportController())->sendTicketReply();
        break;

    // =========================================================================
    // ADMIN
    // =========================================================================

    case "/admin/dashboard":
        (new AdminController())->index();
        break;

    case "/admin/vehicles":
        (new AdminController())->manageVehicles();
        break;

    case "/admin/add-vehicle":
        (new AdminController())->addVehicle();
        break;

    case "/admin/edit-vehicle":
        (new AdminController())->editVehicle();
        break;

    case "/admin/delete-vehicle":
        (new AdminController())->deleteVehicle();
        break;

    case "/admin/bookings":
        (new AdminController())->manageBookings();
        break;

    case "/admin/confirm-booking":
        (new AdminController())->confirmBooking();
        break;

    case "/admin/cancel-booking":
        (new AdminController())->cancelBooking();
        break;

    case "/admin/customers":
        (new AdminController())->manageCustomers();
        break;

    case "/admin/update-role":
        (new AdminController())->updateRole();
        break;

    case "/admin/requests":
        (new AdminController())->manageRequests();
        break;

    case "/admin/approve-car":
        (new AdminController())->approveCar();
        break;

    case "/admin/reject-car":
        (new AdminController())->rejectCar();
        break;

    case "/admin/enquiries":
        (new AdminController())->manageEnquiries();
        break;

    case "/admin/reply-enquiry":
        (new AdminController())->replyEnquiry();
        break;

    case "/admin/tickets":
        (new SupportController())->adminTickets();
        break;

    case "/admin/ticket/reply":
        (new SupportController())->adminReplyTicket();
        break;

    // =========================================================================
    // STAFF
    // =========================================================================

    case "/staff/dashboard":
        (new StaffController())->index();
        break;

    // =========================================================================
    // 404
    // =========================================================================

    default:
        http_response_code(404);
        require "app/views/errors/404.php";
        break;
}