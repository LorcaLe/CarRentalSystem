<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Support - Luxury Car Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; }
        .ticket-sidebar { background: white; border-right: 1px solid #e2e8f0; height: 100vh; overflow-y: auto; }
        .chat-container { height: 100vh; display: flex; flex-direction: column; background: #f8fafc; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 10px; }
        .ticket-item { cursor: pointer; border-bottom: 1px solid #f1f5f9; transition: 0.2s; }
        .ticket-item:hover { background: #f8fafc; }
        .ticket-item.active { background: #eff6ff; border-left: 4px solid #3b82f6; }
        .bubble { max-width: 75%; padding: 12px 16px; border-radius: 18px; font-size: 14px; position: relative; }
        .bubble-admin { align-self: flex-end; background: #3b82f6; color: white; border-bottom-right-radius: 4px; }
        .bubble-user { align-self: flex-start; background: white; color: #1e293b; border-bottom-left-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-lg-3 p-0 ticket-sidebar shadow-sm">
            <div class="p-3 border-bottom bg-white sticky-top d-flex align-items-center">
                <a href="/car_rental/public/admin/dashboard" class="btn btn-sm btn-light rounded-circle me-3" title="Back to Dashboard">
                    <i class="fas fa-home text-primary"></i>
                </a>
                <h5 class="fw-bold m-0 text-dark">Support Center</h5>
            </div>

            <div class="list-group list-group-flush">
                <?php if (!empty($all_tickets)): ?>
                    <?php foreach($all_tickets as $t): ?>
                        <div class="ticket-item p-3 border-bottom" onclick="loadAdminChat(<?= $t['id'] ?>, this)">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-primary fw-bold">#<?= $t['ticket_id'] ?></small>
                                <span class="badge rounded-pill <?= $t['ticket_status'] == 'Open' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $t['ticket_status'] ?>
                                </span>
                            </div>
                            <div class="text-dark small mb-1 fw-medium"><?= htmlspecialchars($t['subject']) ?></div>
                            <div class="text-muted" style="font-size: 11px;">From: <?= htmlspecialchars($t['customer_name'] ?? 'Guest') ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-muted small">No tickets available</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8 col-lg-9 p-0">
            <div id="chatDefault" class="chat-container justify-content-center align-items-center text-muted">
                <i class="fas fa-comments fa-4x mb-3 opacity-25"></i>
                <p>Select a ticket to start responding to customers</p>
            </div>

            <div id="chatContent" class="chat-container" style="display: none;">
                <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold m-0" id="chatSubject">Subject</h6>
                        <small class="text-muted" id="chatCustomer">Customer Name</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger btn-round" id="closeBtn" onclick="endTicketAdmin()">End Ticket</button>
                </div>
                
                <div class="chat-messages" id="adminChatMessages">
                    </div>

                <div class="p-3 bg-white border-top">
                    <div class="input-group">
                        <input type="text" id="adminReplyMsg" class="form-control border-0 bg-light" placeholder="Type your response...">
                        <button class="btn btn-primary px-4" onclick="sendAdminReply()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentTicketId = null;

function loadAdminChat(id, element) {
    currentTicketId = id;
    const chatMessages = document.getElementById('adminChatMessages');
    
    // UI: Đổi màu ticket đang được chọn
    document.querySelectorAll('.ticket-item').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
    
    // Hiện khung chat và ẩn thông báo mặc định
    document.getElementById('chatDefault').style.display = 'none';
    document.getElementById('chatContent').style.display = 'flex';

    // Gọi API lấy dữ liệu (Route viewDetail mà chúng ta đã làm)
    fetch(`/car_rental/public/ticket/view?id=${id}`)
    .then(res => res.json())
    .then(data => {
        if(!data.success) return;

        // 1. Đổ Subject và Ticket ID vào Header
        document.getElementById('chatSubject').innerText = data.info.subject;
        // Lấy tên khách hàng từ cái ticket-item mình vừa click (vì data.info có thể chưa có tên)
        const customerName = element.querySelector('.text-muted').innerText.replace('From: ', '');
        document.getElementById('chatCustomer').innerText = "Customer: " + customerName + " | ID: #" + data.info.ticket_id;

        // 2. Vẽ tin nhắn (Lặp qua bảng ticket_messages)
        let html = '';
        
        // Nếu không có tin nhắn nào trong ticket_messages (lỗi hiếm), hiện tin nhắn gốc
        if(data.replies.length === 0) {
            html = `<div class="bubble bubble-user shadow-sm">${data.info.message}</div>`;
        } else {
            data.replies.forEach(m => {
                const isAdmin = (m.sender_type === 'Admin');
                html += `
                    <div class="bubble ${isAdmin ? 'bubble-admin' : 'bubble-user shadow-sm'}">
                        ${isAdmin ? '<b>You:</b> ' : ''} ${m.message}
                        <div style="font-size: 9px; opacity: 0.6; margin-top: 5px; text-align: ${isAdmin ? 'right' : 'left'}">
                            ${m.created_at}
                        </div>
                    </div>`;
            });
        }

        chatMessages.innerHTML = html;
        
        // Cuộn xuống cuối cùng
        setTimeout(() => { chatMessages.scrollTop = chatMessages.scrollHeight; }, 100);

        // 3. Khóa nút Send nếu ticket đã Closed
        const isClosed = data.info.ticket_status === 'Closed';
        document.getElementById('adminReplyMsg').disabled = isClosed;
        document.getElementById('closeBtn').style.display = isClosed ? 'none' : 'block';
    });
}

function sendAdminReply() {
    const msg = document.getElementById('adminReplyMsg').value.trim();
    if(!msg || !currentTicketId) return;

    fetch('/car_rental/public/admin/ticket/reply', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `ticket_row_id=${currentTicketId}&message=${encodeURIComponent(msg)}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('adminReplyMsg').value = '';
            loadAdminChat(currentTicketId, document.querySelector('.ticket-item.active'));
        }
    });
}

function endTicketAdmin() {
    if(!currentTicketId) return;
    if(confirm('Are you sure you want to close this ticket?')) {
        fetch('/car_rental/public/ticket/close', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + currentTicketId
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) location.reload(); // Reload để cập nhật badge status
        });
    }
}
</script>
</body>
</html>