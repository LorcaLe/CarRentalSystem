<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Center - Luxury Car Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/car_rental/assets/css/style.css">
    <link rel="stylesheet" href="/car_rental/assets/css/layout.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; color: #333; }

        .support-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 0 20px;
            min-height: 70vh;
        }

        /* HEADER STYLES */
        .support-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }
        .support-header h2 { font-size: 32px; font-weight: 700; color: #1a1a1a; margin: 0; }

        /* BUTTONS */
        .btn-round { border-radius: 50px; font-weight: 600; transition: all 0.3s ease; padding: 10px 25px; }
        .btn-new-ticket {
            background-color: #007bff; color: white; border: none;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }
        .btn-new-ticket:hover {
            background-color: #0056b3; transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
            color: white;
        }

        /* TICKET CARD & HOVER EFFECT */
        .ticket-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid transparent;
            margin-bottom: 1.5rem;
        }
        /* Hiệu ứng Hover xịn xò */
        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
            border-color: #007bff;
        }

        /* STATUS BADGES */
        .status-badge {
            padding: 6px 16px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .bg-open { background-color: #e7f3ff; color: #0084ff; }
        .bg-closed { background-color: #f0f2f5; color: #65676b; }

        /* EMPTY STATE */
        .empty-state {
            background: #ffffff;
            border-radius: 24px;
            padding: 80px 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        }
        .empty-state img { width: 120px; margin-bottom: 25px; opacity: 0.5; filter: grayscale(1); }

        /* MODAL FIX */
        .modal-content { border-radius: 24px; overflow: hidden; }
        .form-control-custom {
            background-color: #f8f9fa; border: none; border-radius: 12px; padding: 15px;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . "/../layouts/header.php"; ?>

<div class="support-container">
    <div class="support-header">
        <div>
            <h2>Support Tickets</h2>
            <p class="text-muted m-0">Manage your inquiries and support requests</p>
        </div>
        <button class="btn-round btn-new-ticket" data-bs-toggle="modal" data-bs-target="#newTicketModal">
            <i class="fas fa-plus me-2"></i>New Ticket
        </button>
    </div>

    <div class="row">
        <?php 
        $hasTicket = false;
        foreach($tickets as $t): 
            if(!empty($t['ticket_id'])): 
                $hasTicket = true;
        ?>
            <div class="col-12">
                <div class="card ticket-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="status-badge <?= $t['ticket_status'] == 'Closed' ? 'bg-closed' : 'bg-open' ?>">
                                        <?= $t['ticket_status'] ?>
                                    </span>
                                    <span class="text-muted small fw-bold">#<?= htmlspecialchars($t['ticket_id']) ?></span>
                                </div>
                                <h5 class="fw-bold text-dark mb-2"><?= htmlspecialchars($t['subject'] ?? 'General Support') ?></h5>
                                <div class="text-muted small">
                                    <i class="far fa-calendar-alt me-1"></i> <?= date('d M, Y • H:i', strtotime($t['created_at'])) ?>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <button onclick="viewTicket(<?= $t['id'] ?>)" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#viewTicketModal" 
                                        class="btn btn-sm btn-light border btn-round me-2">
                                    View Detail
                                </button>
                                <?php if($t['ticket_status'] != 'Closed'): ?>
                                    <button class="btn btn-outline-danger btn-round" onclick="endTicket(<?= $t['id'] ?>)">
                                        End Ticket
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            endif;
        endforeach; 

        if(!$hasTicket): ?>
            <div class="col-12">
                <div class="empty-state">
                    <img src="https://cdn-icons-png.flaticon.com/512/6598/6598519.png" alt="No Tickets">
                    <h4>No Active Tickets</h4>
                    <p>You don't have any support tickets yet. Click "New Ticket" to get started with our support team.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="newTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Create New Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/car_rental/public/ticket/create" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject</label>
                        <input type="text" name="subject" class="form-control form-control-custom" placeholder="Briefly describe the issue" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Detailed Message</label>
                        <textarea name="message" class="form-control form-control-custom" rows="4" placeholder="How can we help you today?" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light btn-round px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-round px-5 shadow-sm">Send Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold" id="modalTicketID">Ticket Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="ticketChatFlow" style="height: 300px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; padding: 10px; background: #f8f9fa; border-radius: 15px; margin-bottom: 15px;">
                    </div>

                <div id="replyArea">
                    <div class="d-flex gap-2">
                        <input type="text" id="replyInput" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" placeholder="Type your reply...">
                        <button onclick="sendReply()" class="btn btn-primary btn-round px-4">Send</button>
                    </div>
                </div>
                <div id="closedNotice" class="text-center text-muted small" style="display:none;">
                    This ticket is closed. Please create a new one if you need further help.
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function endTicket(id) {
    if(confirm('Are you sure you want to end this ticket? This action cannot be undone.')) {
        fetch('/car_rental/public/ticket/close', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) location.reload();
        });
    }
}

let currentTicketId = null;

function viewTicket(id) {
    currentTicketId = id; 
    const chatFlow = document.getElementById('ticketChatFlow');
    chatFlow.innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

    fetch(`/car_rental/public/ticket/view?id=${id}`)
    .then(res => res.json())
    .then(data => {
        if(!data.success) return;

        document.getElementById('modalTicketID').innerText = "Ticket #" + data.info.ticket_id;
        
        let html = '';
        // Vẽ tin nhắn gốc
        if(data.info.message) {
            html += `<div style="align-self: flex-end; background: #0084ff; color: white; padding: 10px 15px; border-radius: 18px 18px 4px 18px; max-width: 85%; margin-bottom: 5px; font-size: 14px;">${data.info.message}</div>`;
        }

        // Vẽ các tin nhắn phản hồi
        if (data.replies && data.replies.length > 0) {
            data.replies.forEach(m => {
                const isAdmin = (m.sender_type === 'Admin');
                html += `<div style="align-self: ${isAdmin ? 'flex-start' : 'flex-end'}; background: ${isAdmin ? '#e4e6eb' : '#0084ff'}; color: ${isAdmin ? '#050505' : 'white'}; padding: 10px 15px; border-radius: 18px; margin-bottom: 5px; font-size: 14px; max-width: 85%;">${m.message}</div>`;
            });
        }

        chatFlow.innerHTML = html;
        setTimeout(() => { chatFlow.scrollTop = chatFlow.scrollHeight; }, 100);
        
        // Cập nhật trạng thái đóng/mở
        const isClosed = data.info.ticket_status === 'Closed';
        document.getElementById('replyArea').style.display = isClosed ? 'none' : 'block';
        document.getElementById('closedNotice').style.display = isClosed ? 'block' : 'none';

        // TUYỆT ĐỐI KHÔNG DÙNG DÒNG NÀY NỮA:
        // new bootstrap.Modal(document.getElementById('viewTicketModal')).show(); 
    });
}

function sendReply() {
    const input = document.getElementById('replyInput');
    const message = input.value.trim();
    
    if (!message || !currentTicketId) {
        console.error("Missing ID or Message. ID:", currentTicketId);
        return;
    }

    fetch('/car_rental/public/ticket/reply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `ticket_row_id=${currentTicketId}&message=${encodeURIComponent(message)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            // Gọi lại hàm viewTicket(id) để load lại tin nhắn mà không cần tắt Modal
            viewTicket(currentTicketId); 
        } else {
            alert("Error: " + data.message);
        }
    });
}
</script>
</body>
</html>