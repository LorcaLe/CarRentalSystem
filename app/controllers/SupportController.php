<?php

class SupportController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                header("Location: /car_rental/public/login");
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
            exit;
        }

        require_once __DIR__ . "/../../config/database.php";
        $this->db = (new Database())->conn;
    }

    /**
     * Danh sách Ticket của người dùng
     */
    public function index() {
        $user_id = $_SESSION['user']['id'];
        
        $sql = "SELECT * FROM enquiries 
                WHERE user_id = ? 
                AND ticket_id IS NOT NULL 
                AND ticket_id != '' 
                ORDER BY created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $tickets = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        require_once __DIR__ . "/../views/support/index.php";
    }

    /**
     * Tạo Ticket mới - Lưu Subject vào enquiries và Message vào ticket_messages
     */
    public function createTicket() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'];
            $subject = $_POST['subject'] ?? 'No Subject';
            $message = $_POST['message'] ?? '';
            $ticket_id = "TKT-" . $user_id . "-" . rand(100, 999);

            $this->db->begin_transaction();
            try {
                // 1. Tạo bản ghi gốc (Enquiry)
                $sql = "INSERT INTO enquiries (ticket_id, user_id, subject, ticket_status, created_at) 
                        VALUES (?, ?, ?, 'Open', NOW())";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("sis", $ticket_id, $user_id, $subject);
                $stmt->execute();
                $new_ticket_row_id = $this->db->insert_id;

                // 2. Lưu tin nhắn đầu tiên vào bảng tin nhắn
                $sql_msg = "INSERT INTO ticket_messages (ticket_row_id, sender_type, message) VALUES (?, 'User', ?)";
                $stmt_m = $this->db->prepare($sql_msg);
                $stmt_m->bind_param("is", $new_ticket_row_id, $message);
                $stmt_m->execute();

                $this->db->commit();
                header("Location: /car_rental/public/enquiry?msg=created");
            } catch (Exception $e) {
                $this->db->rollback();
                echo "Error: " . $e->getMessage();
            }
            exit;
        }
    }

    /**
     * Xem chi tiết (Dùng chung cho cả User và Admin Popup)
     */
    public function viewDetail() {
        $id = $_GET['id'] ?? null;
        // Nếu là Admin thì không cần check user_id, nếu là User thì phải check
        $user_id = $_SESSION['user']['id'];
        $is_admin = ($_SESSION['user']['role'] ?? '') === 'admin';

        $sql_tkt = $is_admin 
            ? "SELECT id, ticket_id, subject, ticket_status, created_at FROM enquiries WHERE id = ?"
            : "SELECT id, ticket_id, subject, ticket_status, created_at FROM enquiries WHERE id = ? AND user_id = ?";
        
        $stmt = $this->db->prepare($sql_tkt);
        if ($is_admin) $stmt->bind_param("i", $id);
        else $stmt->bind_param("ii", $id, $user_id);
        
        $stmt->execute();
        $ticket = $stmt->get_result()->fetch_assoc();

        if (!$ticket) {
            echo json_encode(['success' => false, 'message' => 'Not found']);
            exit;
        }

        $sql_msgs = "SELECT sender_type, message, created_at FROM ticket_messages WHERE ticket_row_id = ? ORDER BY created_at ASC";
        $stmt_m = $this->db->prepare($sql_msgs);
        $stmt_m->bind_param("i", $id);
        $stmt_m->execute();
        $replies = $stmt_m->get_result()->fetch_all(MYSQLI_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'info' => $ticket, 'replies' => $replies]);
        exit;
    }

    /**
     * Gửi phản hồi (Dùng chung cho User và Admin)
     */
public function sendTicketReply() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ticket_row_id = $_POST['ticket_row_id'];
        $message = $_POST['message'];

        // SỬA TẠI ĐÂY: 
        // Đảm bảo logic kiểm tra role là chính xác
        $sender = ($_SESSION['user']['role'] === 'admin') ? 'Admin' : 'User';

        $sql = "INSERT INTO ticket_messages (ticket_row_id, sender_type, message) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $ticket_row_id, $sender, $message);
        
        $success = $stmt->execute();
        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}

    public function endTicket() {
        $id = $_POST['id'];
        $sql = "UPDATE enquiries SET ticket_status = 'Closed' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        header('Content-Type: application/json');
        echo json_encode(['success' => $stmt->execute()]);
        exit;
    }

    /**
 * Lấy danh sách toàn bộ Ticket cho Admin
 */
public function adminTickets() {
    if (($_SESSION['user']['role'] ?? '') !== 'admin') {
        header("Location: /car_rental/public/dashboard");
        exit;
    }

    // SQL lấy toàn bộ Ticket + Tên khách hàng
    // Lưu ý: Kiểm tra kỹ tên bảng 'users' và cột 'full_name' trong DB của bạn nhé
    $sql = "SELECT e.*, u.name as customer_name 
            FROM enquiries e 
            JOIN users u ON e.user_id = u.id 
            WHERE e.ticket_id IS NOT NULL 
            ORDER BY CASE WHEN e.ticket_status = 'Open' THEN 1 ELSE 2 END, e.created_at DESC";
            
    $result = $this->db->query($sql);

    // KIỂM TRA LỖI SQL (Phần này sẽ giúp bạn không bị lỗi Fatal Error nữa)
    if (!$result) {
        die("Lỗi SQL: " . $this->db->error . " | Hãy kiểm tra xem bảng 'users' có cột 'full_name' không.");
    }

    $all_tickets = $result->fetch_all(MYSQLI_ASSOC);

    // Lấy số lượng ticket Open cho Sidebar
    $count_query = $this->db->query("SELECT COUNT(*) as open_count FROM enquiries WHERE ticket_status = 'Open'");
    $openTickets = $count_query ? $count_query->fetch_assoc()['open_count'] : 0;

    $data = [
        'all_tickets' => $all_tickets,
        'openTickets' => $openTickets
    ];

    require_once __DIR__ . "/../views/admin/tickets.php";
}

    /**
     * Hàm xử lý Admin gửi phản hồi (Dùng chung logic với User nhưng đổi sender_type)
     */
    public function adminReplyTicket() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ticket_row_id = $_POST['ticket_row_id'];
            $message = $_POST['message'];
            $sender = 'Admin'; // Ép kiểu sender là Admin

            $sql = "INSERT INTO ticket_messages (ticket_row_id, sender_type, message) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iss", $ticket_row_id, $sender, $message);
            
            if ($stmt->execute()) {
                // Cập nhật lại thời gian để Ticket nhảy lên đầu danh sách
                $this->db->query("UPDATE enquiries SET created_at = NOW() WHERE id = $ticket_row_id");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
        }
    }

public function sendEnquiry() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Kiểm tra session user
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $message = $_POST['message'] ?? '';
        
        if (empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Message is empty']);
            exit;
        }

        // Lưu trực tiếp vào bảng enquiries, bỏ qua cột ticket_id
        // Chỉ giữ lại user_id, subject, message và status
        $sql = "INSERT INTO enquiries (user_id, subject, message, ticket_status, created_at) 
                VALUES (?, 'Quick Chat', ?, 'Open', NOW())";
        
        $stmt = $this->db->prepare($sql);
        // Lưu ý: bind_param bây giờ chỉ còn "is" (integer cho user_id, string cho message)
        $stmt->bind_param("is", $user_id, $message);

        header('Content-Type: application/json');
        if ($stmt->execute()) {
            // Trả về id (khóa chính) vừa tự động tăng trong DB
            echo json_encode(['success' => true, 'id' => $this->db->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        exit;
    }
}
public function getEnquiries() {
    // 1. Lấy ID của người dùng từ Session
    $user_id = $_SESSION['user']['id'] ?? null;

    if (!$user_id) {
        echo json_encode([]);
        exit;
    }

    // 2. Truy vấn chỉ trên bảng enquiries
    // Lấy message (của khách) và reply (của admin)
    $sql = "SELECT id, message, reply, created_at 
            FROM enquiries 
            WHERE user_id = ? 
            ORDER BY created_at ASC";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'user_msg' => $row['message'],
            'admin_rep' => $row['reply'],
            'time' => date('H:i', strtotime($row['created_at']))
        ];
    }

    // 3. Trả về định dạng JSON
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
}