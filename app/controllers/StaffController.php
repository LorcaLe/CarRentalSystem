<?php
// app/controllers/StaffController.php

class StaffController {
    
    public function __construct() {
        // Allow both staff and admin to access staff dashboard
        $role = $_SESSION['user']['role'] ?? '';
        if (!isset($_SESSION['user']) || ($role !== 'staff' && $role !== 'admin')) {
            header("Location: /car_rental/public/login");
            exit;
        }
    }

    public function index() {
        require_once __DIR__ . "/../views/staff/dashboard.php";
    }
}