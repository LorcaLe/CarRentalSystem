<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Booking Confirmation</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Vehicle Information</h5>
                    <p><strong>Model:</strong> <?= htmlspecialchars($vehicle['name']) ?> (<?= htmlspecialchars($vehicle['model']) ?>)</p>
                    <p><strong>Daily Rate:</strong> <?= number_format($vehicle['price_per_day']) ?> VND/day</p>
                    <p><strong>Pickup Location:</strong> <?= htmlspecialchars($_POST['pickup_location']) ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Rental Period</h5>
                    <p><strong>Pickup:</strong> <?= $_POST['pickup_date'] ?> at <?= $_POST['pickup_time'] ?></p>
                    <p><strong>Return:</strong> <?= $_POST['return_date'] ?> at <?= $_POST['return_time'] ?></p>
                    <p><strong>Total Duration:</strong> <?= $days ?> Day(s)</p>
                </div>
            </div>

            <hr>

            <div class="text-end mb-4">
                <h3 class="text-danger">Total Price: <?= number_format($total_price) ?> VND</h3>
            </div>

            <form action="/car_rental/public/booking/confirmBooking" method="POST">
                <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                <input type="hidden" name="pickup_location" value="<?= $_POST['pickup_location'] ?>">
                <input type="hidden" name="pickup_date" value="<?= $_POST['pickup_date'] ?>">
                <input type="hidden" name="pickup_time" value="<?= $_POST['pickup_time'] ?>">
                <input type="hidden" name="return_date" value="<?= $_POST['return_date'] ?>">
                <input type="hidden" name="return_time" value="<?= $_POST['return_time'] ?>">
                <input type="hidden" name="total_price" value="<?= $total_price ?>">

                <div class="d-flex justify-content-between">
                    <a href="javascript:history.back()" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success btn-lg">Confirm & Book Now</button>
                </div>
            </form>
        </div>
    </div>
</div>