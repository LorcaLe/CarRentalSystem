<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Center - Luxury Car Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/car_rental/assets/css/enquiry.css">
</head>
<body>

<div class="support-wrapper">
    <aside class="ticket-sidebar shadow-sm">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="fas fa-headset me-2 text-primary"></i>Support</h5>
                <a href="/car_rental/public/" class="btn btn-sm btn-light rounded-circle"><i class="fas fa-home"></i></a>
            </div>
            <button class="btn btn-primary w-100 btn-round py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#newTicketModal">
                <i class="fas fa-plus me-2"></i>New Ticket
            </button>
        </div>

        <div class="ticket-list">
            <?php if (!empty($tickets)): ?>
                <?php foreach($tickets as $t): ?>
                    <div class="ticket-item" onclick="loadUserChat(<?= $t['id'] ?>, this)">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-primary fw-bold">#<?= $t['ticket_id'] ?></small>
                            <span class="badge rounded-pill <?= $t['ticket_status'] == 'Closed' ? 'bg-secondary' : 'bg-success' ?>" style="font-size: 9px;">
                                <?= $t['ticket_status'] ?>
                            </span>
                        </div>
                        <div class="text-dark small fw-bold text-truncate"><?= htmlspecialchars($t['subject']) ?></div>
                        <div class="text-muted" style="font-size: 11px;"><?= date('d M, Y', strtotime($t['created_at'])) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-5 text-center text-muted small">No tickets yet.</div>
            <?php endif; ?>
        </div>
    </aside>

    <main class="chat-area">
        <div id="chatDefault" class="empty-chat">
            <div class="mb-3">
                <i class="fab fa-facebook-messenger fa-4x opacity-25"></i>
            </div>
            <h5 class="fw-bold text-dark">Your Conversations</h5>
            <p class="small text-muted">Select a conversation to view details</p>
        </div>

        <div id="chatContent" style="display: none; flex-direction: column; height: 100%;">
            <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold m-0 text-primary" id="chatSubject">Subject</h6>
                    <small class="text-muted" id="chatStatus">Status: Open</small>
                </div>
                <button class="btn btn-sm btn-outline-danger btn-round" id="endTicketBtn" onclick="closeTicketByUser()">End Conversation</button>
            </div>
            
            <div class="chat-messages" id="chatFlow"></div>

            <div class="chat-footer">
                <div class="input-wrapper">
                    <textarea id="replyMsg" placeholder="Type your message here..."></textarea>
                </div>
                <div class="chat-actions">
                    <small class="text-muted">Use <b>Ctrl + Enter</b> to send quickly.</small>
                    <button class="btn-send-mes shadow-sm" onclick="sendUserReply()">
                        Send Message <i class="fas fa-paper-plane ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="newTicketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">Create New Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/car_rental/public/ticket/create" method="POST">
                <div class="modal-body p-4">
                    <input type="text" name="subject" class="form-control mb-3 p-3 bg-light border-0" placeholder="Subject" style="border-radius:12px" required>
                    <textarea name="message" class="form-control p-3 bg-light border-0" rows="4" placeholder="How can we help?" style="border-radius:12px" required></textarea>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 btn-round py-2 shadow-sm">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
let activeId = null;

function loadUserChat(id, el) {
    activeId = id;
    document.querySelectorAll('.ticket-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    
    document.getElementById('chatDefault').style.display = 'none';
    document.getElementById('chatContent').style.display = 'flex';

    fetch(`/car_rental/public/ticket/view?id=${id}`)
    .then(res => res.json())
    .then(data => {
        if(!data.success) return;
        document.getElementById('chatSubject').innerText = data.info.subject;
        document.getElementById('chatStatus').innerText = "Ticket: #" + data.info.ticket_id + " | Status: " + data.info.ticket_status;

        let html = '';
        data.replies.forEach(m => {
            const isMe = (m.sender_type === 'User');
            // Cập nhật cấu trúc bong bóng Messenger
            html += `
                <div class="bubble ${isMe ? 'bubble-user' : 'bubble-admin'}">
                    ${!isMe ? '<b style="font-size:11px;">Support Team</b><br>' : ''}${m.message}
                    <div style="font-size: 9px; opacity: 0.7; margin-top:5px; text-align: ${isMe ? 'right' : 'left'}">${m.created_at}</div>
                </div>`;
        });
        const flow = document.getElementById('chatFlow');
        flow.innerHTML = html;
        setTimeout(() => { flow.scrollTop = flow.scrollHeight; }, 100);

        const closed = data.info.ticket_status === 'Closed';
        document.getElementById('replyMsg').disabled = closed;
        document.getElementById('endTicketBtn').style.display = closed ? 'none' : 'block';
    });
}

function sendUserReply() {
    const msg = document.getElementById('replyMsg').value.trim();
    if(!msg || !activeId) return;

    fetch('/car_rental/public/ticket/reply', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `ticket_row_id=${activeId}&message=${encodeURIComponent(msg)}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('replyMsg').value = '';
            loadUserChat(activeId, document.querySelector('.ticket-item.active'));
        }
    });
}

function closeTicketByUser() {
    Swal.fire({
        title: 'End Conversation?', text: "You won't be able to reply anymore.",
        icon: 'warning', showCancelButton: true, confirmButtonColor: '#0084ff', confirmButtonText: 'Yes, End it'
    }).then((r) => {
        if(r.isConfirmed) {
            fetch('/car_rental/public/ticket/close', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'id=' + activeId
            }).then(() => location.reload());
        }
    });
}

document.getElementById('replyMsg').addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'Enter') sendUserReply();
});
</script>
</body>
</html>