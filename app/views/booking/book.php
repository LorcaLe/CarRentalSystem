<h2>Book Vehicle</h2>

<form method="POST">

<input type="hidden" name="vehicle_id" value="<?= $_GET['vehicle'] ?>">

<label>Pickup Date</label>
<input type="date" name="pickup_date" required>

<label>Return Date</label>
<input type="date" name="return_date" required>

<button class="btn-gold">Confirm Booking</button>

</form>