<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Car — Partner</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/registerCar.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo" font-size=0.75>PrivateHire Cars  <span >Partner</span> </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Overview</div>
            <a href="/car_rental/public/partner/dashboard" class="nav-item"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <div class="nav-label" style="margin-top:16px;">Fleet</div>
            <a href="/car_rental/public/partner/my-cars" class="nav-item"><i class="fas fa-car"></i> My Cars</a>
            <a href="/car_rental/public/partner/register-car" class="nav-item active"><i class="fas fa-plus-circle"></i> Add New Car</a>
            <div class="nav-label" style="margin-top:16px;">Account</div>
            <a href="/car_rental/public/partner/profile" class="nav-item"><i class="fas fa-user"></i> My Profile</a>
        </nav>
        <div class="sidebar-footer">
            Signed in as <strong style="color:#fff"><?= htmlspecialchars($_SESSION['user']['name']) ?></strong><br>
            <a href="/car_rental/public/logout">Logout</a>
        </div>
    </aside>

    <main class="main">
        <div class="page-header">
            <h1>Register a New Car</h1>
            <p>Fill in the details. Your car will be reviewed within 24 hours before going live.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="/car_rental/public/partner/submit-car" enctype="multipart/form-data">
            <div class="form-layout">

                <!-- Left -->
                <div>
                    <div class="card">
                        <div class="section-label"><i class="fas fa-car me-1"></i> Car Information</div>
                        <div class="grid-2">
                            <div class="field span-2">
                                <label>Car Name</label>
                                <input type="text" name="name" id="f_name" placeholder="e.g. Toyota Camry 2024" required oninput="sync()">
                            </div>
                            <div class="field">
                                <label>Brand</label>
                                <input type="text" name="branch" id="f_brand" 
                                    list="brand-list" placeholder="e.g. Toyota" 
                                    required onchange="sync()" oninput="sync()">
                                <datalist id="brand-list">
                                    <?php foreach (['Toyota','Honda','Mazda','Hyundai','KIA','Ford','Mitsubishi','Suzuki','Vinfast','BMW','Audi','Mercedes-Benz','Lexus'] as $b): ?>
                                        <option value="<?= $b ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            <div class="field">
                                <label>Seats</label>
                                <select name="seats" id="f_seats" required onchange="sync()">
                                    <option value="">Select</option>
                                    <option value="4">4 seats</option>
                                    <option value="5">5 seats</option>
                                    <option value="7">7 seats</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Transmission</label>
                                <select name="transmission" id="f_trans" required onchange="sync()">
                                    <option value="">Select</option>
                                    <option value="Automatic">Automatic</option>
                                    <option value="Manual">Manual</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Fuel Type</label>
                                <select name="fuel_type" id="f_fuel" onchange="sync()">
                                    <option value="Petrol">Petrol</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Electric">Electric</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Price per Day (VND)</label>
                                <input type="number" name="price_per_day" id="f_price"
                                    placeholder="e.g. 900000" min="100000" step="50000"
                                    required oninput="sync()">
                            </div>

                            <div class="field">
                                <label>Pickup Location</label>
                                <input type="text" name="location" id="f_loc"
                                    placeholder="e.g. Ho Chi Minh City"
                                    required oninput="sync()">
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="field">
                            <label>Description</label>
                            <textarea name="description" id="f_desc" rows="3"
                                    placeholder="Briefly describe your car — condition, features, notes for renters..."
                                    oninput="sync()"
                                    style="padding:11px 14px; border:1.5px solid var(--border); border-radius:10px;
                                            font-family:'DM Sans',sans-serif; font-size:0.9rem; color:var(--text);
                                            background:#fafbfd; outline:none; resize:vertical; width:100%;
                                            transition:border-color .2s;"></textarea>
                        </div>
                    </div>
                    <div class="card">
                        <div class="section-label"><i class="fas fa-camera"></i> Car Photo</div>
                        <div class="upload-area">
                            <input type="file" name="image" accept="image/*" required onchange="previewImage(event)">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p><strong>Click to upload</strong> or drag & drop<br>PNG, JPG — max 5MB</p>
                            <img id="preview">
                        </div>
                    </div>
                </div>

                <!-- Right: sticky summary -->
                <div class="summary-wrap">
                    <div class="summary-card">
                        <h3>📋 Preview Summary</h3>
                        <div class="s-row"><span class="s-lbl">Car Name</span>    <span class="s-val" id="s_name">—</span></div>
                        <div class="s-row"><span class="s-lbl">Brand</span>        <span class="s-val" id="s_brand">—</span></div>
                        <div class="s-row"><span class="s-lbl">Seats</span>        <span class="s-val" id="s_seats">—</span></div>
                        <div class="s-row"><span class="s-lbl">Transmission</span> <span class="s-val" id="s_trans">—</span></div>
                        <div class="s-row"><span class="s-lbl">Fuel</span>         <span class="s-val" id="s_fuel">—</span></div>
                        <div class="s-row"><span class="s-lbl">Location</span>     <span class="s-val" id="s_loc">—</span></div>
                        <div class="s-row"><span class="s-lbl">Price / Day</span>  <span class="s-val" id="s_price">—</span></div>

                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            After submission, our team will review your car within <strong>24 hours</strong> before it appears on the platform.
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Submit for Review
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <script>
        function sync() {
            const v = id => document.getElementById(id).value;
            const set = (id, val) => document.getElementById(id).textContent = val || '—';

            set('s_name',  v('f_name'));
            set('s_brand', v('f_brand'));
            set('s_seats', v('f_seats') ? v('f_seats') + ' seats' : '');
            set('s_trans', v('f_trans'));
            set('s_fuel',  v('f_fuel'));
            set('s_loc',   v('f_loc'));

            const price = parseInt(v('f_price'));
            set('s_price', price ? new Intl.NumberFormat('vi-VN').format(price) + ' VND' : '');
        }

        function previewImage(e) {
            const file = e.target.files[0];
            if (!file) return;
            const p = document.getElementById('preview');
            p.src = URL.createObjectURL(file);
            p.style.display = 'block';
        }
    </script>
</body>
</html>