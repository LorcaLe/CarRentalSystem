<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partner Dashboard — CarRental</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&family=DM+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/car_rental/assets/css/partner.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php
    $ownerId = $_SESSION['user']['id'];
    $months  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $currentMonth = (int)date('n');
    $currentYear  = (int)date('Y');

    // Dữ liệu tháng đang chọn (mặc định tháng hiện tại)
    $selectedMonth = (int)($_GET['month'] ?? $currentMonth);
    $daysInMonth   = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $currentYear);

    // Tạo mảng daily data
    $dailyBookings = array_fill(1, $daysInMonth, 0);
    $dailyRevenue  = array_fill(1, $daysInMonth, 0);

    if (isset($stats['daily_data'])) {
        foreach ($stats['daily_data'] as $row) {
            $d = (int)$row['day'];
            $dailyBookings[$d] = (int)$row['bookings'];
            $dailyRevenue[$d]  = (int)$row['revenue'];
        }
    }

    $dayLabels = array_keys($dailyBookings);

    // Revenue per car
    $carRevenue = $stats['car_revenue'] ?? [];
    $maxRev     = !empty($carRevenue) ? max(array_column($carRevenue, 'revenue')) : 1;
?>

    <aside class="sidebar">
        <div class="sidebar-logo">PrivateHire Cars  <span >Partner</span> </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Overview</div>
            <a href="/car_rental/public/partner/dashboard" class="nav-item active"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <div class="nav-label" style="margin-top:16px;">Fleet</div>
            <a href="/car_rental/public/partner/my-cars" class="nav-item"><i class="fas fa-car"></i> My Cars</a>
            <a href="/car_rental/public/partner/register-car" class="nav-item"><i class="fas fa-plus-circle"></i> Add New Car</a>
            <div class="nav-label" style="margin-top:16px;">Account</div>
            <a href="/car_rental/public/partner/profile" class="nav-item"><i class="fas fa-user"></i> My Profile</a>
        </nav>
        <div class="sidebar-footer">
            Signed in as <strong style="color:#fff"><?= htmlspecialchars($_SESSION['user']['name']) ?></strong><br>
            <a href="/car_rental/public/logout">Logout</a>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1>Good morning, <span><?= htmlspecialchars(explode(' ', $_SESSION['user']['name'])[0]) ?> 👋</span></h1>
            <div class="topbar-user">
                <div class="avatar"><?= strtoupper($_SESSION['user']['name'][0]) ?></div>
                <?= htmlspecialchars($_SESSION['user']['name']) ?>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-icon ic-blue"><i class="fas fa-car"></i></div>
                <div><div class="stat-val"><?= $stats['total']          ?? 0 ?></div><div class="stat-lbl">Total Cars</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon ic-green"><i class="fas fa-check-circle"></i></div>
                <div><div class="stat-val"><?= $stats['approved']       ?? 0 ?></div><div class="stat-lbl">Approved</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon ic-amber"><i class="fas fa-calendar-check"></i></div>
                <div><div class="stat-val"><?= $stats['total_bookings'] ?? 0 ?></div><div class="stat-lbl">Bookings <?= $currentYear ?></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon ic-purple"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="stat-val" style="font-size:1.1rem;"><?= number_format($stats['total_revenue'] ?? 0) ?> VND</div>
                    <div class="stat-lbl">Revenue <?= $currentYear ?></div>
                </div>
            </div>
        </div>

        <!-- Daily chart + Donut -->
        <div class="chart-row">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Daily Overview</div>
                    <div class="chart-controls">
                        <div class="tab-group">
                            <button class="tab-btn active" onclick="switchChart('bookings', this)">Bookings</button>
                            <button class="tab-btn"        onclick="switchChart('revenue',  this)">Revenue</button>
                        </div>
                        <!-- Month selector -->
                        <select class="month-select" id="monthSelect" onchange="changeMonth(this.value)">
                            <?php foreach ($months as $mi => $mn): ?>
                                <option value="<?= $mi + 1 ?>" <?= ($mi + 1 === $selectedMonth) ? 'selected' : '' ?>>
                                    <?= $mn ?> <?= $currentYear ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <canvas id="mainChart" height="100"></canvas>
            </div>

            <!-- Fleet status donut -->
            <div class="card">
                <div class="card-header"><div class="card-title">Fleet Status</div></div>
                <div class="donut-wrap">
                    <canvas id="donutChart" width="140" height="140" style="max-width:140px;"></canvas>
                </div>
                <div class="donut-legend">
                    <div class="legend-item"><div class="legend-dot" style="background:#22d3a5"></div> Approved <strong style="margin-left:auto"><?= $stats['approved'] ?? 0 ?></strong></div>
                    <div class="legend-item"><div class="legend-dot" style="background:#f59e0b"></div> Pending  <strong style="margin-left:auto"><?= $stats['pending']  ?? 0 ?></strong></div>
                    <div class="legend-item"><div class="legend-dot" style="background:#f87171"></div> Rejected <strong style="margin-left:auto"><?= $stats['rejected'] ?? 0 ?></strong></div>
                </div>
            </div>
        </div>

        <!-- Revenue by car -->
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <div class="card-title">💰 Revenue by Car — <?= $months[$selectedMonth - 1] ?> <?= $currentYear ?></div>
            </div>
            <?php if (empty($carRevenue)): ?>
                <div class="empty-state">
                    <i class="fas fa-chart-bar"></i>
                    <p>No booking data for this month yet.</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Car</th>
                            <th>Bookings</th>
                            <th>Revenue</th>
                            <th>Share</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carRevenue as $row):
                            $pct = $maxRev > 0 ? round(($row['revenue'] / $maxRev) * 100) : 0;
                            $s   = strtolower($row['status'] ?? 'pending');
                            $cls = $s === 'approved' ? 'b-approved' : ($s === 'pending' ? 'b-pending' : 'b-rejected');
                        ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <img src="/car_rental/images/<?= htmlspecialchars($row['image']) ?>" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                        <span class="car-name"><?= htmlspecialchars($row['name']) ?></span>
                                    </div>
                                </td>
                                <td><?= $row['bookings'] ?> booking(s)</td>
                                <td class="mono"><?= number_format($row['revenue']) ?> VND</td>
                                <td style="min-width:140px;">
                                    <div class="rev-bar-wrap">
                                        <div class="rev-bar" style="width:<?= $pct ?>%"></div>
                                        <span style="font-size:0.75rem;color:var(--muted);"><?= $pct ?>%</span>
                                    </div>
                                </td>
                                <td><span class="badge <?= $cls ?>"><?= ucfirst($s) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <script>
        const dayLabels   = <?= json_encode(array_values($dayLabels)) ?>;
        const bookData    = <?= json_encode(array_values($dailyBookings)) ?>;
        const revData     = <?= json_encode(array_values($dailyRevenue)) ?>;

        // ── Daily line chart ───────────────────────────────────────────────
        const ctx = document.getElementById('mainChart').getContext('2d');

        function makeGradient(color) {
            const g = ctx.createLinearGradient(0, 0, 0, 220);
            g.addColorStop(0,   color.replace('1)', '0.15)'));
            g.addColorStop(1,   color.replace('1)', '0)'));
            return g;
        }

        let currentType = 'bookings';
        let mainChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dayLabels,
                datasets: [{
                    label: 'Bookings',
                    data: bookData,
                    borderColor: '#3b6ef8',
                    backgroundColor: makeGradient('rgba(59,110,248,1)'),
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#3b6ef8',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => currentType === 'revenue'
                                ? ' ' + new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VND'
                                : ' ' + ctx.raw + ' booking(s)'
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 11 }, maxTicksLimit: 15 } },
                    y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 }, precision: 0 }, beginAtZero: true }
                }
            }
        });

        function switchChart(type, btn) {
            currentType = type;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const isRev = type === 'revenue';
            const color = isRev ? 'rgba(34,211,165,1)' : 'rgba(59,110,248,1)';
            mainChart.data.datasets[0].data                 = isRev ? revData  : bookData;
            mainChart.data.datasets[0].label                = isRev ? 'Revenue' : 'Bookings';
            mainChart.data.datasets[0].borderColor           = isRev ? '#22d3a5' : '#3b6ef8';
            mainChart.data.datasets[0].pointBackgroundColor  = isRev ? '#22d3a5' : '#3b6ef8';
            mainChart.data.datasets[0].backgroundColor       = makeGradient(color);
            mainChart.update();
        }

        // Month change → reload page with ?month=X
        function changeMonth(m) {
            window.location.href = '/car_rental/public/partner/dashboard?month=' + m;
        }

        // ── Donut ──────────────────────────────────────────────────────────
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Approved','Pending','Rejected'],
                datasets: [{
                    data: [
                        <?= (int)($stats['approved'] ?? 0) ?>,
                        <?= (int)($stats['pending']  ?? 0) ?>,
                        <?= (int)($stats['rejected'] ?? 0) ?>
                    ],
                    backgroundColor: ['#22d3a5','#f59e0b','#f87171'],
                    borderWidth: 0, hoverOffset: 4,
                }]
            },
            options: { cutout:'72%', plugins:{ legend:{ display:false } }, responsive:false }
        });
    </script>
</body>
</html>