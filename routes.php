<?php
// public/index.php

session_start();

// Import tất cả Controller
require_once "app/controllers/AuthController.php";
require_once "app/controllers/VehicleController.php";
require_once "app/controllers/BookingController.php";
require_once "app/controllers/SupportController.php";
require_once "app/controllers/PaymentController.php";
require_once "app/controllers/AdminController.php";
require_once "app/controllers/StaffController.php";

// Xử lý URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace("/car_rental/public", "", $uri);

switch ($uri) {

    /* --- GIAO DIỆN NGƯỜI DÙNG (GUEST/USER) --- */
    case "/":
        (new VehicleController())->index();
        break;

    case "/car-detail":
        (new VehicleController())->getCarDetail();
        break;

    case "/search-cars":
        (new VehicleController())->searchCars();
        break;

    /* --- HỆ THỐNG TÀI KHOẢN (AUTH) --- */
    case "/login":
        (new AuthController())->login();
        break;

    case "/register":
        (new AuthController())->register();
        break;

    case "/logout":
        (new AuthController())->logout();
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

    /* --- ĐẶT XE (BOOKING) --- */
    case "/booking-form":
        (new BookingController())->showBookingForm();
        break;

    case "/book-car":
        (new BookingController())->calculateBooking();
        break;

    case "/confirm-booking":
        (new BookingController())->confirmBooking();
        break;

    case "/booking-success":
        require "app/views/booking/success.php";
        break;

    case "/my_booking":
        (new BookingController())->myBookings();
        break;

    case "/cancel-booking":
        (new BookingController())->cancelBooking();
        break;

    case "/checkout":
        (new PaymentController())->index();
        break;

    /* --- KÝ GỬI XE (CONSIGNMENT) --- */
    case "/register-car":
        (new VehicleController())->showConsignmentForm();
        break;

    case "/submit-consignment":
        (new VehicleController())->submitConsignment();
        break;

    /* --- QUẢN TRỊ VIÊN (ADMIN) --- */
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

    // Duyệt xe ký gửi
    case "/admin/requests":
        (new AdminController())->manageRequests();
        break;

    case "/admin/approve-car":
        (new AdminController())->approveCar();
        break;

    case "/admin/reject-car":
        (new AdminController())->rejectCar();
        break;

    /* --- NHÂN VIÊN (STAFF) --- */
    case "/staff/dashboard":
        (new StaffController())->index();
        break;

    /* --- HỖ TRỢ KHÁCH HÀNG (SUPPORT) --- */
    case "/enquiry":
        (new SupportController())->index(); // Hoặc hàm tương ứng để hiện trang hỗ trợ
    break;
    
    case "/send-enquiry":
        (new SupportController())->sendEnquiry();
    break;

    case "/admin/enquiries":
        (new AdminController())->manageEnquiries();
    break;

    case "/admin/reply-enquiry":
        (new AdminController())->replyEnquiry();
    break;

    case '/ticket/create':
        (new SupportController())->createTicket();
        break;

    // 3. API Đóng Ticket (Dùng cho nút "End Ticket")
    case '/ticket/close':
        (new SupportController())->endTicket();
        break;

    // 4. Xem chi tiết một Ticket (Trang chat nội bộ của Ticket)
    case '/ticket/view':
        (new SupportController())->viewDetail(); // Bạn cần viết thêm hàm này trong Controller
        break;

    // Route để gửi tin nhắn phản hồi (Reply)
    case '/ticket/reply':
        (new SupportController())->sendTicketReply();
        break;

    // 2. Route lấy lịch sử tin nhắn (Dùng để hiển thị lên khung chat)
    case '/get-enquiries':
        (new SupportController())->getEnquiries();
        break;
    /* --- MẶC ĐỊNH --- */

    case '/admin/tickets':
    (new SupportController())->adminTickets();
    break;

    case '/admin/ticket/reply':
        (new SupportController())->adminReplyTicket();
        break;
    default:
        http_response_code(404);
        echo "404 page not found";
        break;
}